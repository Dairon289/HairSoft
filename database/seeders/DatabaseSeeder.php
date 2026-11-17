<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SeguridadSeeder::class,
            ServicioSeeder::class,
            EmpleadoSeeder::class,
            EmpleadoServicioSeeder::class,
            ClienteSeeder::class,
            CuentaPruebaSeeder::class,
            CitaSeeder::class,
        ]);
    }
}