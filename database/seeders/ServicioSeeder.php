<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServicioSeeder extends Seeder
{
    public function run(): void
    {
        $servicios = [
            [
                'nombreServicio' => 'Corte de cabello',
                'duracionServicio' => '00:45:00',
                'descripcionServicio' => 'Corte para dama o caballero con lavado incluido',
                'precioServicio' => 25000.00,
                'estadoServicio' => 'activo',
            ],
            [
                'nombreServicio' => 'Tinte',
                'duracionServicio' => '02:00:00',
                'descripcionServicio' => 'Aplicación de color completo en el cabello',
                'precioServicio' => 80000.00,
                'estadoServicio' => 'activo',
            ],
            [
                'nombreServicio' => 'Peinado',
                'duracionServicio' => '01:00:00',
                'descripcionServicio' => 'Peinado para eventos o uso diario',
                'precioServicio' => 40000.00,
                'estadoServicio' => 'activo',
            ],
            [
                'nombreServicio' => 'Manicure',
                'duracionServicio' => '01:00:00',
                'descripcionServicio' => 'Limpieza, limado y esmaltado de uñas de las manos',
                'precioServicio' => 30000.00,
                'estadoServicio' => 'activo',
            ],
            [
                'nombreServicio' => 'Pedicure',
                'duracionServicio' => '01:15:00',
                'descripcionServicio' => 'Limpieza, limado y esmaltado de uñas de los pies',
                'precioServicio' => 35000.00,
                'estadoServicio' => 'activo',
            ],
            [
                'nombreServicio' => 'Tratamiento capilar',
                'duracionServicio' => '01:30:00',
                'descripcionServicio' => 'Hidratación y reparación profunda del cabello',
                'precioServicio' => 60000.00,
                'estadoServicio' => 'activo',
            ],
        ];

        $ahora = now();

        foreach ($servicios as $servicio) {
            DB::table('servicios')->updateOrInsert(
                ['nombreServicio' => $servicio['nombreServicio']],
                $servicio + ['created_at' => $ahora, 'updated_at' => $ahora]
            );
        }
    }
}