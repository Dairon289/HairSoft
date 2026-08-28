<?php

namespace App\Repositories;

use App\Models\empleado;

class EmpleadoRepository
{

    public function listarTodo()
    {
        return empleado::all();
    }


    public function guardar(array $datos)
    {
       empleado::create($datos);  
    }
    
    public function eliminar(int $id)
    {
        $empleado = empleado::findOrFail($id);
        $empleado->delete();
    }

    public function buscarporid(int $id)
    {
        return empleado::findOrFail($id);
    }

    public function actualizar(int $id, array $datos)
    {
        $empleado = empleado::findOrFail($id);
        $empleado->update($datos);
    }
}