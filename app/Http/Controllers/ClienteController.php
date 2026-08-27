<?php

namespace App\Http\Controllers;

use App\Models\cliente;
use Illuminate\Http\Request;
use App\Services\ClienteService;

class ClienteController extends Controller
{
    private ClienteService $cliente_service;

    public function __construct(ClienteService $clienteservice)
    {
        $this->cliente_service = $clienteservice;
    }

    public function index()
    {
        $clientes = $this->cliente_service->listarTodo();

        return view('clientes.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $datos = $request->all();

        $this->cliente_service->guardar($datos);

        return redirect()->route('cliente.index')->with('success', 'Cliente creado correctamente');
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
        $cliente = $this->cliente_service->buscarporid($id);

        return view('clientes.edit', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $datos = $request->all();

        $this->cliente_service->actualizar($id, $datos);

        return redirect()->route('cliente.index')->with('successedit', 'Cliente actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $this->cliente_service->eliminar($id);

        return redirect()->route('cliente.index')->with('successdelete', 'Cliente eliminado correctamente');
    }
}