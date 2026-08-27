<?php

namespace App\Repositories;

use App\Models\cliente;

class ClienteRepository
{

    public function listarTodo()
    {
        return cliente::all();
    }


    public function guardar(array $datos)
    {
       cliente::create($datos);  
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
    }
}