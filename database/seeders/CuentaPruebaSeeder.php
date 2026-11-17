<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CuentaPruebaSeeder extends Seeder
{
    public function run(): void
    {
        $modelo = config('auth.providers.users.model');
        $guard = config('seguridad.guard');
        $password = Hash::make('Prueba12345');

        // Cuenta de cliente (enlazada al cliente Ana Martínez)
        $clienteId = DB::table('clientes')
            ->where('correo', 'ana.martinez@example.com')
            ->value('id');

        if ($clienteId) {
            $usuarioCliente = $modelo::firstOrCreate(
                ['email' => 'ana.martinez@example.com'],
                ['name' => 'Ana Martínez', 'password' => $password]
            );
            $usuarioCliente->cliente_id = $clienteId;
            $usuarioCliente->save();
            $usuarioCliente->assignRole(
                Role::findByName(config('seguridad.rol_cliente'), $guard)
            );
        }

        // Cuenta de empleado (enlazada a la empleada Laura Gómez)
        $empleadoId = DB::table('empleados')
            ->where('correoEmpleado', 'laura.gomez@hairsoft.com')
            ->value('id');

        if ($empleadoId) {
            $usuarioEmpleado = $modelo::firstOrCreate(
                ['email' => 'laura.gomez@hairsoft.com'],
                ['name' => 'Laura Gómez', 'password' => $password]
            );
            $usuarioEmpleado->empleado_id = $empleadoId;
            $usuarioEmpleado->save();
            $usuarioEmpleado->assignRole(
                Role::findByName(config('seguridad.rol_empleado'), $guard)
            );
        }
    }
}