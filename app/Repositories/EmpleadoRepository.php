<?php

namespace App\Repositories;

use App\Models\empleado;
use App\Models\servicio;

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

    public function obtenerServiciosAsignados(int $id)
    {
        $empleado = empleado::findOrFail($id);

        return $empleado->servicios->pluck('id')->toArray();
    }


    public function sincronizarServicios(int $id, array $idsServicios)
    {
        $empleado = empleado::findOrFail($id);

        $empleado->servicios()->sync($idsServicios);
    }
}