<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitaSeeder extends Seeder
{
    public function run(): void
    {
        // Evita duplicar citas si el seeder se ejecuta más de una vez
        if (DB::table('citas')->exists()) {
            return;
        }

        $hoy = Carbon::today();
        $ahora = now();

        // dia: 0 = hoy, 1 = mañana, -1 = ayer, etc.
        $citas = [
            ['dia' => -1, 'hora' => '09:00', 'cliente' => 'ana.martinez@example.com', 'empleado' => 'laura.gomez@hairsoft.com', 'servicios' => ['Corte de cabello'], 'estado' => 'finalizada'],
            ['dia' => -1, 'hora' => '14:00', 'cliente' => 'sofia.herrera@example.com', 'empleado' => 'marcela.torres@hairsoft.com', 'servicios' => ['Manicure'], 'estado' => 'cancelada'],
            ['dia' => 0, 'hora' => '10:00', 'cliente' => 'juan.perez@example.com', 'empleado' => 'carlos.ramirez@hairsoft.com', 'servicios' => ['Corte de cabello', 'Peinado'], 'estado' => 'iniciada'],
            ['dia' => 0, 'hora' => '13:00', 'cliente' => 'ana.martinez@example.com', 'empleado' => 'laura.gomez@hairsoft.com', 'servicios' => ['Tinte'], 'estado' => 'confirmada'],
            ['dia' => 1, 'hora' => '11:00', 'cliente' => 'sofia.herrera@example.com', 'empleado' => 'andres.castillo@hairsoft.com', 'servicios' => ['Tratamiento capilar'], 'estado' => 'pendiente'],
            ['dia' => 2, 'hora' => '09:00', 'cliente' => 'juan.perez@example.com', 'empleado' => 'marcela.torres@hairsoft.com', 'servicios' => ['Manicure', 'Pedicure'], 'estado' => 'confirmada'],
            ['dia' => 3, 'hora' => '10:00', 'cliente' => 'ana.martinez@example.com', 'empleado' => 'carlos.ramirez@hairsoft.com', 'servicios' => ['Peinado'], 'estado' => 'pendiente'],
        ];

        foreach ($citas as $c) {
            $clienteId = DB::table('clientes')->where('correo', $c['cliente'])->value('id');
            $empleadoId = DB::table('empleados')->where('correoEmpleado', $c['empleado'])->value('id');
            $servicios = DB::table('servicios')->whereIn('nombreServicio', $c['servicios'])->get();

            if (!$clienteId || !$empleadoId || $servicios->isEmpty()) {
                continue;
            }

            // Hora de inicio y fin programadas (fin = inicio + duración de los servicios)
            $inicio = $hoy->copy()->addDays($c['dia'])->setTimeFromTimeString($c['hora']);

            $minutos = 0;
            foreach ($servicios as $servicio) {
                [$horas, $mins] = array_map('intval', explode(':', $servicio->duracionServicio));
                $minutos += ($horas * 60) + $mins;
            }
            $fin = $inicio->copy()->addMinutes($minutos);

            $datos = [
                'cliente_id' => $clienteId,
                'empleado_id' => $empleadoId,
                'fecha_programada' => $inicio->toDateTimeString(),
                'fecha_fin_programada' => $fin->toDateTimeString(),
                'estado' => $c['estado'],
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ];

            if ($c['estado'] === 'iniciada') {
                $datos['fecha_inicio'] = $inicio->toDateTimeString();
            }

            if ($c['estado'] === 'finalizada') {
                $datos['fecha_inicio'] = $inicio->toDateTimeString();
                $datos['fecha_fin'] = $fin->toDateTimeString();
            }

            if ($c['estado'] === 'cancelada') {
                $datos['fechaCancelacion'] = $inicio->copy()->subHours(5)->toDateTimeString();
                $datos['motivoCancelacion'] = 'El cliente no pudo asistir';
            }

            $citaId = DB::table('citas')->insertGetId($datos);

            foreach ($servicios as $servicio) {
                DB::table('detalleServicios')->insert([
                    'cita_id' => $citaId,
                    'servicio_id' => $servicio->id,
                    'precioServicio' => $servicio->precioServicio,
                    'created_at' => $ahora,
                    'updated_at' => $ahora,
                ]);
            }
        }
    }
}