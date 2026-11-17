<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmpleadoRequest;
use App\Http\Requests\UpdateEmpleadoRequest;
use App\Services\EmpleadoService;
use App\Services\ServicioService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmpleadoController extends Controller
{
    private EmpleadoService $empleado_service;
    private ServicioService $servicio_service;

    public function __construct(EmpleadoService $empleadoservice, ServicioService $servicioservice)
    {
        $this->empleado_service = $empleadoservice;
        $this->servicio_service = $servicioservice;
    }

    public function index(): View
    {
        $empleado = $this->empleado_service->listarTodo();

        return view('empleados.index', compact('empleado'));
    }

    public function create(): View
    {
        $servicios = $this->servicio_service->listarTodo();

        return view('empleados.create', compact('servicios'));
    }

    public function store(StoreEmpleadoRequest $request): RedirectResponse
    {
        $validados = $request->validated();
        $idsServicios = $validados['servicios'] ?? [];
        unset($validados['servicios']);

        $this->empleado_service->guardar($validados, $idsServicios);

        return redirect()->route('empleados.index')->with('success', 'Empleado creado correctamente');
    }

    public function edit(int $id): View
    {
        $empleado = $this->empleado_service->buscarporid($id);
        $servicios = $this->servicio_service->listarTodo();
        $serviciosAsignados = $this->empleado_service->obtenerServiciosAsignados($id);

        return view('empleados.edit', compact('empleado', 'servicios', 'serviciosAsignados'));
    }

    public function update(UpdateEmpleadoRequest $request, int $id): RedirectResponse
    {
        $validados = $request->validated();
        $idsServicios = $validados['servicios'] ?? [];
        unset($validados['servicios']);

        $this->empleado_service->actualizar($id, $validados);
        $this->empleado_service->sincronizarServicios($id, $idsServicios);

        return redirect()->route('empleados.index')->with('successedit', 'Empleado actualizado correctamente');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->empleado_service->eliminar($id);

        return redirect()->route('empleados.index')->with('successdelete', 'Empleado eliminado correctamente');
    }
}
