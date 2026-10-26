<?php

namespace App\Http\Controllers;

use App\Models\cliente;
use Illuminate\Http\Request;
use App\Services\ClienteService;
use Illuminate\Database\QueryException;
use Illuminate\Validation\Rule;

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
        $datos = $request->validate([
            'nombre'   => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'correo'   => 'required|email|max:255|unique:clientes,correo',
            'telefono' => 'required|string|max:255',
            'rol'      => 'required|in:cliente,administrador',
            'estado'   => 'required|in:activo,inactivo',
            'password' => 'required|string|min:8',
        ]);

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
        $datos = $request->validate([
            'nombre'   => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'correo'   => ['required', 'email', 'max:255', Rule::unique('clientes', 'correo')->ignore($id)],
            'telefono' => 'required|string|max:255',
            'rol'      => 'required|in:cliente,administrador',
            'estado'   => 'required|in:activo,inactivo',
            'password' => 'nullable|string|min:8',
        ]);

        if (empty($datos['password'])) {
            unset($datos['password']);
        }

        $this->cliente_service->actualizar($id, $datos);

        return redirect()->route('cliente.index')->with('success', 'Cliente actualizado correctamente');
    }   

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try {
            $this->cliente_service->eliminar($id);
        } catch (QueryException $e) {
            report($e);

            return redirect()->route('cliente.index')
                ->with('errordelete', 'No se pudo eliminar el cliente por un problema con la base de datos. Inténtalo de nuevo.');
        } catch (\Exception $e) {
            return redirect()->route('cliente.index')
                ->with('errordelete', $e->getMessage());
        }

        return redirect()->route('cliente.index')
            ->with('successdelete', 'Cliente eliminado correctamente');
    }
}