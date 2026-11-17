<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {   
        
        $usuario = $request->user();

        if ($usuario->hasRole(config('seguridad.rol_cliente'))) {
            return redirect()->route('citas.index');
        }

        if ($usuario->hasRole(config('seguridad.rol_empleado'))) {
            return redirect()->route('agenda.index');
        }

        $inicioMes         = now()->startOfMonth();
        $finMes            = now()->endOfMonth();
        $inicioMesAnterior = now()->startOfMonth()->subMonthNoOverflow();
        $finMesAnterior    = $inicioMesAnterior->copy()->endOfMonth();

        // Ingresos del mes actual y del anterior (solo pagos cobrados)
        $totalRevenue = Pago::where('estado_pago', 'pagado')
            ->whereBetween('fecha_pago', [$inicioMes, $finMes])
            ->sum('monto');

        $ingresosMesAnterior = Pago::where('estado_pago', 'pagado')
            ->whereBetween('fecha_pago', [$inicioMesAnterior, $finMesAnterior])
            ->sum('monto');

        // Variación contra el mes anterior
        if ($ingresosMesAnterior > 0) {
            $variacion     = (($totalRevenue - $ingresosMesAnterior) / $ingresosMesAnterior) * 100;
            $revenueChange = ($variacion >= 0 ? '+' : '-') . number_format(abs($variacion), 1) . '%';
            $revenueTrend  = $variacion >= 0 ? 'up' : 'down';
        } else {
            $revenueChange = $totalRevenue > 0 ? 'Nuevo' : '0%';
            $revenueTrend  = 'up';
        }

        // Pagos pendientes por cobrar
        $pagosPendientes = Pago::where('estado_pago', 'pendiente')->sum('monto');

        // Citas de hoy (sin contar las canceladas)
        $citasHoy = DB::table('citas')
            ->whereBetween('fecha_programada', [now()->startOfDay(), now()->endOfDay()])
            ->where('estado', '!=', 'cancelada')
            ->count();

        // Clientes nuevos del mes (solo rol cliente)
        $clientesNuevos = DB::table('clientes')
            ->where('rol', 'cliente')
            ->whereBetween('created_at', [$inicioMes, $finMes])
            ->count();

        // Ventas de los últimos 6 meses
        $nombresMes = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        $desde      = $inicioMes->copy()->subMonthsNoOverflow(5);

        $ventasPorMes = Pago::where('estado_pago', 'pagado')
            ->whereBetween('fecha_pago', [$desde, $finMes])
            ->get(['monto', 'fecha_pago'])
            ->groupBy(fn ($pago) => $pago->fecha_pago->format('Y-m'))
            ->map(fn ($grupo) => (float) $grupo->sum('monto'));

        $months = [];
        $sales  = [];
        for ($i = 5; $i >= 0; $i--) {
            $mes      = $inicioMes->copy()->subMonthsNoOverflow($i);
            $months[] = $nombresMes[$mes->month - 1];
            $sales[]  = $ventasPorMes->get($mes->format('Y-m'), 0);
        }

        // Últimas 5 citas creadas
        $ultimasCitas = DB::table('citas')
            ->join('clientes', 'clientes.id', '=', 'citas.cliente_id')
            ->select('citas.id', 'citas.estado', 'citas.fecha_programada', 'clientes.nombre', 'clientes.apellido')
            ->orderByDesc('citas.created_at')
            ->orderByDesc('citas.id')
            ->limit(5)
            ->get();

        $chartData = [
            'months' => $months,
            'sales'  => $sales,
            'users'  => [120, 190, 150, 260, 210, 300], // ejemplo
        ];

        return view('dashboard.index', compact(
            'totalRevenue',
            'revenueChange',
            'revenueTrend',
            'pagosPendientes',
            'citasHoy',
            'clientesNuevos',
            'ultimasCitas',
            'chartData'
        ));
    }

    public function adminIndex(Request $request)
    {
        return $this->index($request);
    }
}