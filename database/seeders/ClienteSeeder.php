<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        $clientes = [
            [
                'nombre' => 'Ana',
                'apellido' => 'Martínez',
                'correo' => 'ana.martinez@example.com',
                'telefono' => '3101112233',
            ],
            [
                'nombre' => 'Juan',
                'apellido' => 'Pérez',
                'correo' => 'juan.perez@example.com',
                'telefono' => '3102223344',
            ],
            [
                'nombre' => 'Sofía',
                'apellido' => 'Herrera',
                'correo' => 'sofia.herrera@example.com',
                'telefono' => '3103334455',
            ],
        ];

        $ahora = now();
        $password = Hash::make('Prueba12345');

        foreach ($clientes as $cliente) {
            DB::table('clientes')->updateOrInsert(
                ['correo' => $cliente['correo']],
                $cliente + [
                    'password' => $password,
                    'rol' => 'cliente',
                    'estado' => 'activo',
                    'created_at' => $ahora,
                    'updated_at' => $ahora,
                ]
            );
        }
    }
}