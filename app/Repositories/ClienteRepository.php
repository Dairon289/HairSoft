<?php

namespace App\Repositories;

use App\Models\cliente;
use App\Models\User;

class ClienteRepository
{

    public function listarTodo()
    {
        return cliente::all();
    }


    public function guardar(array $datos)
    {
        return cliente::create($datos);  
    }
    
    public function eliminar(int $id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();
    }

    public function buscarporid(int $id)
    {
        return cliente::findOrFail($id);
    }

    public function actualizar(int $id, array $datos)
    {
        $cliente = cliente::findOrFail($id);
        $cliente->update($datos);

        return $cliente;
    }

    public function tieneCitas(int $id): bool
    {
        return cliente::findOrFail($id)->citas()->exists();
    }

    public function sincronizarCuenta(cliente $cliente, ?string $passwordPlano = null): void
    {
        $cuenta = User::where('cliente_id', $cliente->id)->first();

        if ($cuenta === null && empty($passwordPlano)) {
            return;
        }

        $atributos = [
            'name'  => trim($cliente->nombre . ' ' . $cliente->apellido),
            'email' => $cliente->correo,
        ];

        if (! empty($passwordPlano)) {
            $atributos['password'] = $passwordPlano;
        }

        if ($cuenta === null) {
            $cuenta = User::create($atributos + ['cliente_id' => $cliente->id]);
            $cuenta->assignRole(config('seguridad.rol_cliente'));

            return;
        }

        $cuenta->update($atributos);
    }
}