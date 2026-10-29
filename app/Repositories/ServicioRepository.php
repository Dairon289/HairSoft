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

    public function tieneCitas(int $id): bool
    {
        $servicio = servicio::findOrFail($id);

        return $servicio->detalleServicios()->exists();
    }

    public function tieneCitasActivasFuturas(int $id): bool
    {
        $servicio = servicio::findOrFail($id);

        return $servicio->detalleServicios()
            ->whereHas('cita', function ($query) {
                $query->whereIn('estado', ['pendiente', 'confirmada'])
                    ->where('fecha_programada', '>=', now()->startOfDay());
            })
            ->exists();
    }
}