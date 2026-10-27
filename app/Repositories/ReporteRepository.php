<?php

namespace App\Repositories;

use App\Models\Pago;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReporteRepository
{
    public function totalIngresos(Carbon $inicio, Carbon $fin): float
    {
        return (float) Pago::where('estado_pago', 'pagado')
            ->whereBetween('fecha_pago', [$inicio, $fin])
            ->sum('monto');
    }

    public function ingresosPorServicio(Carbon $inicio, Carbon $fin)
    {
        return DB::table('pagos')
            ->join('detalleServicios', 'detalleServicios.cita_id', '=', 'pagos.cita_id')
            ->join('servicios', 'servicios.id', '=', 'detalleServicios.servicio_id')
            ->where('pagos.estado_pago', 'pagado')
            ->whereBetween('pagos.fecha_pago', [$inicio, $fin])
            ->groupBy('servicios.id', 'servicios.nombreServicio')
            ->select(
                'servicios.nombreServicio',
                DB::raw('COUNT(*) as cantidad'),
                DB::raw('SUM(detalleServicios.precioServicio) as subtotal')
            )
            ->orderByDesc('subtotal')
            ->get();
    }
}