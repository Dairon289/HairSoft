<?php

namespace App\Repositories;

use App\Models\servicio;

class ServicioRepository
{

    public function listarTodo()
    {
        return servicio::all();
    }


    public function guardar(array $datos)
    {
       servicio::create($datos);  
    }
    
    public function eliminar(int $id)
    {
        $servicio = servicio::findOrFail($id);
        $servicio->delete();
    }

    public function buscarporid(int $id)
    {
        return servicio::findOrFail($id);
    }

    public function actualizar(int $id, array $datos)
    {
        $servicio = servicio::findOrFail($id);
        $servicio->update($datos);
    }
}