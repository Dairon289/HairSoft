<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Services\CitaService;
use App\Services\ClienteService;
use App\Services\EmpleadoService;
use App\Services\ServicioService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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

    public function create()
    {
        $clientes = $this->clienteService->listarTodo();
        $empleados = $this->empleadoService->listarTodo();
        $servicios = $this->servicioService->listarTodo();

        return view('citas.create', compact('clientes', 'empleados', 'servicios'));
    }

    public function index(Request $request, ServicioService $servicioService)
    {
        $filtros = $request->validate([
            'desde'       => 'nullable|date',
            'hasta'       => 'nullable|date|after_or_equal:desde',
            'servicio_id' => 'nullable|exists:servicios,id',
        ]);

        $desde = $filtros['desde'] ?? null;
        $hasta = $filtros['hasta'] ?? null;
        $servicioId = isset($filtros['servicio_id']) ? (int) $filtros['servicio_id'] : null;

        $citas = $this->citaService->obtenerTodas($desde, $hasta, $servicioId);
        $servicios = $servicioService->listarTodo();

        return view('citas.index', compact('citas', 'servicios', 'desde', 'hasta', 'servicioId'));
    }

    public function store(Request $request)
    {
        $datosValidados = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'empleado_id' => 'required|exists:empleados,id',
            'fecha_programada' => 'required|date',
            'estado' => 'required|in:pendiente,confirmada,iniciada,finalizada',
            'fecha_inicio' => 'nullable|date|required_if:estado,iniciada,finalizada',
            'fecha_fin' => 'nullable|date|required_if:estado,finalizada|after:fecha_inicio',
            'servicios' => 'required|array',
            'servicios.*' => 'exists:servicios,id',
        ]);

        $this->citaService->create($datosValidados);

        return redirect()
            ->route('citas.index')
            ->with('mensaje', 'Cita creada correctamente.');
    }

    public function edit(int $citas)
    {
        $citas = $this->citaService->obtenerPorId($citas);

        if ($citas->estado === 'cancelada') {
            return redirect()
                ->route('citas.index')
                ->with('error', 'Una cita cancelada no se puede editar. Solo puedes modificar su motivo de cancelación.');
        }

        $clientes = $this->clienteService->listarTodo();
        $empleados = $this->empleadoService->listarTodo();
        $servicios = $this->servicioService->listarTodo();

        return view('citas.edit', compact('citas', 'clientes', 'empleados', 'servicios'));
    }

    public function update(Request $request, int $id)
    {   
        $datosValidados = $request->validate([
            
            'cliente_id' => 'required|exists:clientes,id',
            'empleado_id' => 'required|exists:empleados,id',
            'estado' => 'required|in:pendiente,confirmada,iniciada,finalizada',
            'fecha_programada' => 'required|date',
            'fecha_inicio' => 'required_if:estado,iniciada,finalizada|nullable|date',
            'fecha_fin' => 'required_if:estado,finalizada|nullable|date|after:fecha_inicio',
            'servicios' => 'required|array',
            'servicios.*' => 'exists:servicios,id',
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
        $datos = $request->validate([
            'motivoCancelacion' => 'required|string|min:5|max:100', 
        ]);

        try {
            $this->citaService->cancelar($cita->id, $datos['motivoCancelacion']);
        } catch (\Exception $e) {
            return redirect()
                ->route('citas.index')
                ->with('error', $e->getMessage());
        }

        return redirect()
            ->route('citas.index')
            ->with('success', 'La cita fue cancelada correctamente.');
    }

    public function formularioCancelar(Cita $cita)
    {
        if (!$this->citaService->puedeCancelarse($cita)) {
            return redirect()
                ->route('citas.index')
                ->with('error', 'Esta cita ya no se puede cancelar.');
        }

        return view('citas.cancelar', compact('cita'));
    }

    public function formularioMotivo(Cita $cita)
    {
        if ($cita->estado !== 'cancelada') {
            return redirect()
                ->route('citas.index')
                ->with('error', 'Solo se puede editar el motivo de una cita cancelada.');
        }

        return view('citas.editar-motivo', compact('cita'));
    }

    public function actualizarMotivo(Request $request, Cita $cita): RedirectResponse
    {
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

    public function factura(int $id)
    {
        try {
            $cita = $this->citaService->obtenerParaFactura($id);
        } catch (\DomainException $e) {
            return redirect()
                ->route('citas.index')
                ->with('error', $e->getMessage());
        }

        return view('citas.factura', compact('cita'));
    }
}