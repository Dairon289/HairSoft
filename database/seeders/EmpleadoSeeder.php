<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmpleadoSeeder extends Seeder
{
    public function run(): void
    {
        $empleados = [
            [
                'nombreEmpleado' => 'Laura',
                'apellidoEmpleado' => 'Gómez',
                'correoEmpleado' => 'laura.gomez@hairsoft.com',
                'telefonoEmpleado' => '3001112233',
                'disponibilidad' => 'disponible',
                'fechaIngreso' => '2024-02-01',
                'horaIngreso' => '08:00:00',
                'horaSalida' => '17:00:00',
                'estadoEmpleado' => 'activo',
            ],
            [
                'nombreEmpleado' => 'Carlos',
                'apellidoEmpleado' => 'Ramírez',
                'correoEmpleado' => 'carlos.ramirez@hairsoft.com',
                'telefonoEmpleado' => '3002223344',
                'disponibilidad' => 'disponible',
                'fechaIngreso' => '2024-05-15',
                'horaIngreso' => '09:00:00',
                'horaSalida' => '18:00:00',
                'estadoEmpleado' => 'activo',
            ],
            [
                'nombreEmpleado' => 'Marcela',
                'apellidoEmpleado' => 'Torres',
                'correoEmpleado' => 'marcela.torres@hairsoft.com',
                'telefonoEmpleado' => '3003334455',
                'disponibilidad' => 'disponible',
                'fechaIngreso' => '2023-11-10',
                'horaIngreso' => '08:00:00',
                'horaSalida' => '16:00:00',
                'estadoEmpleado' => 'activo',
            ],
            [
                'nombreEmpleado' => 'Andrés',
                'apellidoEmpleado' => 'Castillo',
                'correoEmpleado' => 'andres.castillo@hairsoft.com',
                'telefonoEmpleado' => '3004445566',
                'disponibilidad' => 'disponible',
                'fechaIngreso' => '2025-01-20',
                'horaIngreso' => '10:00:00',
                'horaSalida' => '19:00:00',
                'estadoEmpleado' => 'activo',
            ],
            [
                'nombreEmpleado' => 'Daniela',
                'apellidoEmpleado' => 'Rojas',
                'correoEmpleado' => 'daniela.rojas@hairsoft.com',
                'telefonoEmpleado' => '3005556677',
                'disponibilidad' => 'no disponible',
                'fechaIngreso' => '2025-06-02',
                'horaIngreso' => '08:00:00',
                'horaSalida' => '17:00:00',
                'estadoEmpleado' => 'activo',
            ],
        ];

        $ahora = now();

        foreach ($empleados as $empleado) {
            DB::table('empleados')->updateOrInsert(
                ['correoEmpleado' => $empleado['correoEmpleado']],
                $empleado + ['created_at' => $ahora, 'updated_at' => $ahora]
            );
        }
    }
}