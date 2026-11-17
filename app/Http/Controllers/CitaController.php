<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCitaRequest;
use App\Models\Cita;
use App\Services\CitaService;
use App\Services\ClienteService;
use App\Services\EmpleadoService;
use App\Services\ServicioService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CitaController extends Controller
{
    protected CitaService $citaService;
    protected ClienteService $clienteService;
    protected EmpleadoService $empleadoService;
    protected ServicioService $servicioService;

    public function __construct(
        CitaService $citaService,
        ClienteService $clienteService,
        EmpleadoService $empleadoService,
        ServicioService $servicioService
    ) {
        $this->citaService = $citaService;
        $this->clienteService = $clienteService;
        $this->empleadoService = $empleadoService;
        $this->servicioService = $servicioService;
    }

    public function create(Request $request)
    {
        $clienteCuenta = $this->clienteDeLaCuenta($request);
        $esCliente = $clienteCuenta !== null;

        $clientes = $esCliente ? collect() : $this->clienteService->listarTodo();
        $empleados = $this->empleadoService->listarTodo();
        $servicios = $this->servicioService->listarTodo()
            ->filter(fn ($servicio) => strtolower($servicio->estadoServicio) === 'activo')
            ->values();

        return view('citas.create', compact('clientes', 'empleados', 'servicios', 'esCliente'));
    }

    public function index(Request $request, ServicioService $servicioService)
    {
        $filtros = $request->validate([
            'desde'         => 'nullable|date',
            'hasta'         => 'nullable|date|after_or_equal:desde',
            'servicio_id'   => 'nullable|exists:servicios,id',
            'pago_pendiente' => 'nullable|boolean',
        ]);

        $desde = $filtros['desde'] ?? null;
        $hasta = $filtros['hasta'] ?? null;
        $servicioId = isset($filtros['servicio_id']) ? (int) $filtros['servicio_id'] : null;
        $pagoPendiente = $request->boolean('pago_pendiente');
        $clienteId = $this->clienteDeLaCuenta($request);

        $citas = $this->citaService->obtenerTodas($desde, $hasta, $servicioId, $pagoPendiente, $clienteId);
        $servicios = $servicioService->listarTodo();

        return view('citas.index', compact('citas', 'servicios', 'desde', 'hasta', 'servicioId', 'pagoPendiente'));
    }

    public function store(StoreCitaRequest $request)
    {
        $clienteCuenta = $this->clienteDeLaCuenta($request);

        if ($clienteCuenta !== null) {
            $request->merge([
                'cliente_id'   => $clienteCuenta,
                'estado'       => 'pendiente',
                'fecha_inicio' => null,
                'fecha_fin'    => null,
            ]);
        }

        $this->citaService->create($request->validated());

        return redirect()
            ->route('citas.index')
            ->with('mensaje', 'Cita creada correctamente.');
    }

    public function edit(Request $request, int $citas)
    {
        $this->bloquearSiEsCliente($request);

        $citas = $this->citaService->obtenerPorId($citas);

        if ($citas->estado === 'cancelada') {
            return redirect()
                ->route('citas.index')
                ->with('error', 'Una cita cancelada no se puede editar. Solo puedes modificar su motivo de cancelación.');
        }

        if ($citas->estado === 'finalizada') {
            return redirect()
                ->route('citas.index')
                ->with('error', 'Una cita finalizada no se puede editar. Puedes consultar su factura desde el listado.');
        }

        $idsActuales = $citas->detalleServicios
            ->pluck('servicio_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        $clientes = $this->clienteService->listarTodo();
        $empleados = $this->empleadoService->listarTodo();

        $servicios = $this->servicioService->listarTodo()
            ->filter(fn ($servicio) => strtolower($servicio->estadoServicio) === 'activo'
                || in_array((int) $servicio->id, $idsActuales, true))
            ->values();

        return view('citas.edit', compact('citas', 'clientes', 'empleados', 'servicios'));
    }

    public function update(Request $request, int $id)
    {
        $this->bloquearSiEsCliente($request);

        $cita = $this->citaService->obtenerPorId($id);

        if (in_array($cita->estado, ['cancelada', 'finalizada'], true)) {
            return redirect()
                ->route('citas.index')
                ->with('error', 'Esta cita ya no se puede editar.');
        }

        $idsActuales = $cita->detalleServicios
            ->pluck('servicio_id')
            ->map(fn ($servicioId) => (int) $servicioId)
            ->all();

        if (! $cita->datosEditables()) {
            $request->merge([
                'cliente_id' => $cita->cliente_id,
                'empleado_id' => $cita->empleado_id,
                'fecha_programada' => $cita->fecha_programada?->format('Y-m-d H:i'),
                'fecha_inicio' => $cita->fecha_inicio?->format('Y-m-d H:i'),
                'servicios' => $idsActuales,
            ]);
        }

        if ($cita->estado === 'pendiente') {
            $request->merge(['fecha_inicio' => null]);
        }
 
        if ($cita->fecha_inicio === null) {
            $request->merge(['fecha_fin' => null]);
        }

        $datosValidados = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'empleado_id' => 'required|exists:empleados,id',
            'estado' => ['required', Rule::in($cita->estadosPermitidos())],
            'fecha_programada' => 'required|date',
            'fecha_inicio' => 'required_if:estado,iniciada,finalizada|nullable|date',
            'fecha_fin' => 'required_if:estado,finalizada|nullable|date|after:fecha_inicio',
            'servicios' => 'required|array',
            'servicios.*' => [
                Rule::exists('servicios', 'id')->where(function ($query) use ($idsActuales) {
                    $query->where(function ($grupo) use ($idsActuales) {
                        $grupo->where('estadoServicio', 'activo')
                            ->orWhereIn('id', $idsActuales);
                    });
                }),
            ],
        ], [
            'estado.in' => 'Estado no permitido: la cita solo puede avanzar al siguiente paso.',
            'servicios.*.exists' => 'Uno de los servicios seleccionados ya no está disponible. Quítalo e intenta de nuevo.',
        ]);

        try {
            $this->citaService->actualizar($id, $datosValidados);
        } catch (\DomainException $e) {
            return redirect()
                ->route('citas.index')
                ->with('error', $e->getMessage());
        }

        return redirect()->route('citas.index')->with('mensaje', 'Cita actualizada correctamente.');
    }

    public function cancelar(Request $request, Cita $cita): RedirectResponse
    {
        $this->verificarCitaDeLaCuenta($request, $cita);

        $datos = $request->validate([
            'motivoCancelacion' => 'required|string|min:5|max:100',
        ]);

        try {
            $this->citaService->cancelar($cita->id, $datos['motivoCancelacion']);
        } catch (\Exception $e) {
            return redirect()
                ->route('citas.index')
                ->with('error', 'Esta cita no se puede cancelar: ya inició, finalizó, fue cancelada o está programada para hoy.');
        }

        return redirect()
            ->route('citas.index')
            ->with('success', 'La cita fue cancelada correctamente.');
    }

    public function formularioCancelar(Request $request, Cita $cita)
    {
        $this->verificarCitaDeLaCuenta($request, $cita);

        if (!$this->citaService->puedeCancelarse($cita)) {
            return redirect()
                ->route('citas.index')
                ->with('error', 'Esta cita ya no se puede cancelar.');
        }

        return view('citas.cancelar', compact('cita'));
    }

    public function formularioMotivo(Request $request, Cita $cita)
    {
        $this->bloquearSiEsCliente($request);

        if ($cita->estado !== 'cancelada') {
            return redirect()
                ->route('citas.index')
                ->with('error', 'Solo se puede editar el motivo de una cita cancelada.');
        }

        return view('citas.editar-motivo', compact('cita'));
    }

    public function actualizarMotivo(Request $request, Cita $cita): RedirectResponse
    {
        $this->bloquearSiEsCliente($request);

        $datos = $request->validate([
            'motivoCancelacion' => 'required|string|min:5|max:100',
        ]);

        try {
            $this->citaService->actualizarMotivo($cita->id, $datos['motivoCancelacion']);
        } catch (\DomainException $e) {
            return redirect()
                ->route('citas.index')
                ->with('error', $e->getMessage());
        }

        return redirect()
            ->route('citas.index')
            ->with('success', 'El motivo de cancelación fue actualizado correctamente.');
    }

    public function factura(Request $request, int $id)
    {
        $clienteCuenta = $this->clienteDeLaCuenta($request);

        try {
            $cita = $this->citaService->obtenerParaFactura($id);
        } catch (\DomainException $e) {
            return redirect()
                ->route('citas.index')
                ->with('error', $e->getMessage());
        }

        if ($clienteCuenta !== null && (int) $cita->cliente_id !== $clienteCuenta) {
            abort(403);
        }

        return view('citas.factura', compact('cita'));
    }

    private function clienteDeLaCuenta(Request $request): ?int
    {
        $usuario = $request->user();

        if ($usuario->cliente_id !== null) {
            return (int) $usuario->cliente_id;
        }

        if ($usuario->hasRole(config('seguridad.rol_cliente'))) {
            abort(403, 'Tu cuenta no está vinculada a un cliente. Pide al administrador que la vincule.');
        }

        return null;
    }

    private function verificarCitaDeLaCuenta(Request $request, Cita $cita): void
    {
        $clienteCuenta = $this->clienteDeLaCuenta($request);

        if ($clienteCuenta !== null && (int) $cita->cliente_id !== $clienteCuenta) {
            abort(403);
        }
    }

    public function iniciar(Request $request, Cita $cita): RedirectResponse
    {
        $this->verificarCitaDelEmpleado($request, $cita);

        try {
            $this->citaService->iniciar($cita->id);
        } catch (\DomainException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'La cita fue iniciada correctamente.');
    }

    public function finalizar(Request $request, Cita $cita): RedirectResponse
    {
        $this->verificarCitaDelEmpleado($request, $cita);

        try {
            $this->citaService->finalizar($cita->id);
        } catch (\DomainException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->back()->with('success', 'La cita fue finalizada correctamente.');
    }

    private function verificarCitaDelEmpleado(Request $request, Cita $cita): void
    {
        $this->bloquearSiEsCliente($request, 'Solo el personal del salón puede iniciar o finalizar una cita.');

        $usuario = $request->user();

        if ($usuario->empleado_id !== null) {
            if ((int) $cita->empleado_id !== (int) $usuario->empleado_id) {
                abort(403);
            }
            return;
        }

        if ($usuario->hasRole(config('seguridad.rol_empleado'))) {
            abort(403, 'Tu cuenta no está vinculada a un empleado. Pide al administrador que la vincule.');
        }
    }
    
    private function bloquearSiEsCliente(
        Request $request,
        string $mensaje = 'Como cliente puedes crear y cancelar tus citas y consultar tu factura, pero no editarlas. Si necesitas un cambio, comunícate con el salón.'
    ): void {
        if ($this->clienteDeLaCuenta($request) !== null) {
            abort(403, $mensaje);
        }
    }
}