<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmpleadoServicioSeeder extends Seeder
{
    public function run(): void
    {
        // correo del empleado => servicios que realiza
        $asignaciones = [
            'laura.gomez@hairsoft.com' => ['Corte de cabello', 'Tinte', 'Peinado'],
            'carlos.ramirez@hairsoft.com' => ['Corte de cabello', 'Peinado'],
            'marcela.torres@hairsoft.com' => ['Manicure', 'Pedicure'],
            'andres.castillo@hairsoft.com' => ['Tratamiento capilar', 'Peinado'],
            'daniela.rojas@hairsoft.com' => ['Corte de cabello', 'Tinte'],
        ];

        $ahora = now();

        foreach ($asignaciones as $correo => $servicios) {
            $empleadoId = DB::table('empleados')
                ->where('correoEmpleado', $correo)
                ->value('id');

            if (!$empleadoId) {
                continue;
            }

            foreach ($servicios as $nombreServicio) {
                $servicioId = DB::table('servicios')
                    ->where('nombreServicio', $nombreServicio)
                    ->value('id');

                if (!$servicioId) {
                    continue;
                }

                DB::table('empleadoServicio')->updateOrInsert(
                    ['empleado_id' => $empleadoId, 'servicio_id' => $servicioId],
                    ['created_at' => $ahora, 'updated_at' => $ahora]
                );
            }
        }
    }
}