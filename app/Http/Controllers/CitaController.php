<?php

namespace App\Http\Controllers;

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

    public function index()
    {
        $citas = $this->citaService->obtenerTodas();

        return view('citas.index', compact('citas'));
    }

    public function store(Request $request)
    {
        $datosValidados = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'empleado_id' => 'required|exists:empleados,id',
            'fecha_programada' => 'required|date',
            'estado' => 'required|in:pendiente,confirmada,iniciada,cancelada,finalizada',
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
            'estado' => 'required|in:pendiente,confirmada,iniciada,cancelada,finalizada',
            'fecha_programada' => 'required|date',
            'fecha_inicio' => 'required_if:estado,iniciada,finalizada|nullable|date',
            'fecha_fin' => 'required_if:estado,finalizada|nullable|date|after:fecha_inicio',
        ]);

        $this->citaService->actualizar($id, $datosValidados);

        return redirect()->route('citas.index')->with('mensaje', 'Cita actualizada correctamente.');
    }

    public function cancelar(Request $request, int $cita): RedirectResponse
    {
        $motivoCancelacion = $request->input('motivoCancelacion');

        try {
            $this->citaService->cancelar($cita, $motivoCancelacion);
        } catch (\Exception $e) {
            return redirect()
                ->route('citas.index')
                ->with('error', $e->getMessage());
        }

        return redirect()
            ->route('citas.index')
            ->with('success', 'La cita fue cancelada correctamente.');
    }
}