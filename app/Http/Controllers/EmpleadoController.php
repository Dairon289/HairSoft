<?php

namespace App\Http\Controllers;

use App\Models\cliente;
use Illuminate\Http\Request;
use App\Services\EmpleadoService;

class EmpleadoController extends Controller
{
    private EmpleadoService $empleado_service;

    public function __construct(EmpleadoService $empleadoservice)
    {
        $this->empleado_service = $empleadoservice;
    }

    public function index()
    {
        $empleado = $this->empleado_service->listarTodo();

        return view('empleados.index', compact('empleado'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('empleados.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $datos = $request->all();

        $this->empleado_service->guardar($datos);

        return redirect()->route('empleados.index')->with('success', 'Empleado creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $empleado = $this->empleado_service->buscarporid($id);

        return view('empleados.edit', compact('empleado'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $datos = $request->all();

        $this->empleado_service->actualizar($id, $datos);

        return redirect()->route('empleados.index')->with('successedit', 'Empleado actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $this->empleado_service->eliminar($id);

        return redirect()->route('empleados.index')->with('successdelete', 'Empleado eliminado correctamente');
    }
}