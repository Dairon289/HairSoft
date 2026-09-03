<?php

namespace App\Http\Controllers;

use App\Models\servicio;
use Illuminate\Http\Request;
use App\Services\ServicioService;

class ServicioController extends Controller
{
    
    private ServicioService $servicio_service;

    public function __construct(ServicioService $servicioservice)
    {
        $this->servicio_service = $servicioservice;
    }


    public function index()
    {
        $servicios = $this->servicio_service->listarTodo();

        return view('servicios.index', compact('servicios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('servicios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $datos = $request->all();

        $this->servicio_service->guardar($datos);

        return redirect()->route('servicio.index')->with('success', 'Servicio creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id ,servicio $servicios)
    {
        $servicios = $this->servicio_service->buscarporid($id);

        return view('servicios.edit', compact('servicios'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $id , Request $request )
    {
       $datos = $request->all();

        $this->servicio_service->actualizar($id, $datos);

        return redirect()->route('servicio.index')->with('successedit', 'servicio actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $this->servicio_service->eliminar($id);

        return redirect()->route('servicio.index')->with('successdelete', 'Empleado eliminado correctamente');
    }
}
