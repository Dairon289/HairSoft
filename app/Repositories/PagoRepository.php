<?php

namespace App\Repositories;

use App\Models\Pago;

class PagoRepository
{
    
    public function obtenerTodos()
    {
        return Pago::with('cita.detalleServicios.servicio')->latest()->get();
    }

    public function obtenerPorId(int $id): Pago
    {
        return Pago::with('cita')->findOrFail($id);
    }

    public function crear(array $datos): Pago
    {
        return Pago::create($datos);
    }

    public function actualizar(Pago $pago, array $datos): Pago
    {
        $pago->update($datos);
        return $pago;
    }
}