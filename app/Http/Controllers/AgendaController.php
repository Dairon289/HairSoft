<?php

namespace App\Http\Controllers;

use App\Services\CitaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    private const COLORES = [
        'pendiente'  => '#eab308',
        'confirmada' => '#2563eb',
        'iniciada'   => '#16a34a',
        'finalizada' => '#6b7280',
        'cancelada'  => '#dc2626',
        'vencida'    => '#dc2626',
    ];

    protected CitaService $citaService;

    public function __construct(CitaService $citaService)
    {
        $this->citaService = $citaService;
    }

    public function index(Request $request)
    {
        $this->empleadoDeLaCuenta($request);

        return view('agenda.index');
    }

    public function eventos(Request $request): JsonResponse
    {
        $empleadoId = $this->empleadoDeLaCuenta($request);
        $usuario = $request->user();

        $rango = $request->validate([
            'start' => 'required|date',
            'end'   => 'required|date',
        ]);

        $citas = $this->citaService->obtenerAgenda($empleadoId, $rango['start'], $rango['end']);

        $eventos = $citas->map(function ($cita) use ($usuario) {
            $cliente = $cita->cliente->nombre . ' ' . $cita->cliente->apellido;
            $servicios = $cita->detalleServicios->pluck('servicio.nombreServicio')->implode(', ');

            $estaVencida = $cita->estaVencida();
            $estadoVisual = $estaVencida ? 'vencida' : $cita->estado;

            $puedeIniciar = ! $estaVencida && $cita->estado === 'confirmada' && $usuario->can('iniciar citas');
            $puedeFinalizar = $cita->estado === 'iniciada' && $usuario->can('finalizar citas');

            if ($cita->estado === 'cancelada') {
                $inicio = 'No iniciada';
                $fin = 'No finalizada';
            } elseif ($estaVencida) {
                $inicio = 'Vencida (Superó ' . \App\Models\Cita::MINUTOS_TOLERANCIA . ' min de espera)';
                $fin = 'No iniciada';
            } else {
                $inicio = $cita->fecha_inicio ? $cita->fecha_inicio->format('d/m/Y h:i A') : 'Pendiente';
                $fin = $cita->fecha_fin
                    ? $cita->fecha_fin->format('d/m/Y h:i A')
                    : ($cita->estado === 'iniciada' ? 'En curso' : 'Pendiente');
            }

            return [
                'id'        => $cita->id,
                'title'     => ($estaVencida ? '[VENCIDA] ' : '') . $cliente . ' - ' . $servicios,
                'start'     => $cita->fecha_programada->toIso8601String(),
                'end'       => $cita->fecha_fin_programada?->toIso8601String(),
                'color'     => self::COLORES[$estadoVisual] ?? '#6b7280',
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'cliente'      => $cliente,
                    'servicios'    => $servicios,
                    'estado'       => ucfirst($estadoVisual),
                    'programada'   => $cita->fecha_programada->format('d/m/Y h:i A'),
                    'inicio'       => $inicio,
                    'fin'          => $fin,
                    'estaVencida'  => $estaVencida,
                    'urlIniciar'   => $puedeIniciar ? route('citas.iniciar', $cita) : null,
                    'urlFinalizar' => $puedeFinalizar ? route('citas.finalizar', $cita) : null,
                ],
            ];
        });

        return response()->json($eventos);
    }

    private function empleadoDeLaCuenta(Request $request): ?int
    {
        $usuario = $request->user();

        if ($usuario->empleado_id !== null) {
            return (int) $usuario->empleado_id;
        }

        if ($usuario->hasRole(config('seguridad.rol_empleado'))) {
            abort(403, 'Tu cuenta no está vinculada a un empleado. Pide al administrador que la vincule.');
        }

        return null;
    }
}