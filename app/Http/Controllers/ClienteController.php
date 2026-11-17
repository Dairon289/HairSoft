<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;
use App\Services\ClienteService;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ClienteController extends Controller
{
    private ClienteService $cliente_service;

    public function __construct(ClienteService $clienteservice)
    {
        $this->cliente_service = $clienteservice;
    }

    public function index(): View
    {
        $clientes = $this->cliente_service->listarTodo();

        return view('clientes.index', compact('clientes'));
    }

    public function create(): View
    {
        return view('clientes.create');
    }

    public function store(StoreClienteRequest $request): RedirectResponse
    {
        $this->cliente_service->guardar($request->validated());

        return redirect()->route('cliente.index')->with('success', 'Cliente creado correctamente');
    }

    public function edit(int $id): View
    {
        $cliente = $this->cliente_service->buscarporid($id);

        return view('clientes.edit', compact('cliente'));
    }

    public function update(UpdateClienteRequest $request, int $id): RedirectResponse
    {
        $this->cliente_service->actualizar($id, $request->validated());

        return redirect()->route('cliente.index')->with('successedit', 'Cliente actualizado correctamente');
    }

    public function destroy(int $id): RedirectResponse
    {
        try {
            $this->cliente_service->eliminar($id);
            return redirect()->route('cliente.index')->with('successdelete', 'Cliente eliminado correctamente');
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return redirect()->route('cliente.index')->with('error', 'No se puede eliminar el cliente porque tiene citas registradas.');
            }
            return redirect()->route('cliente.index')->with('error', 'Error al eliminar el cliente.');
        }
    }
}