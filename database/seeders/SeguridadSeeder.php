<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class SeguridadSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $guard = config('seguridad.guard');

        $permisoAdmin = Permission::firstOrCreate(
            ['name' => config('seguridad.permiso_admin'), 'guard_name' => $guard],
            ['modulo' => 'seguridad']
        );

        if ($permisoAdmin->modulo === null) {
            $permisoAdmin->update(['modulo' => 'seguridad']);
        }

        $this->crearPermisosDeModulos($guard);

        $rolAdmin = Role::firstOrCreate(
            ['name' => config('seguridad.rol_admin'), 'guard_name' => $guard],
            ['activo' => true]
        );
        $rolAdmin->update(['activo' => true]);
        $rolAdmin->syncPermissions(Permission::where('guard_name', $guard)->get());

        $rolCliente = Role::firstOrCreate(
            ['name' => config('seguridad.rol_cliente'), 'guard_name' => $guard],
            ['activo' => true]
        );
        $rolCliente->givePermissionTo(['ver citas', 'crear citas', 'ver servicios']);

        $modelo = config('auth.providers.users.model');
        $datos = config('seguridad.admin');

        $admin = $modelo::firstOrCreate(
            ['email' => $datos['email']],
            ['name' => $datos['name'], 'password' => Hash::make($datos['password'])]
        );
        $admin->assignRole($rolAdmin);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    private function crearPermisosDeModulos(string $guard): void
    {
        $modulos = ['citas', 'servicios', 'clientes', 'empleados', 'pagos', 'reportes'];
        $acciones = config('seguridad.acciones');

        foreach ($modulos as $modulo) {
            $accionesModulo = $modulo === 'citas'
                ? array_merge($acciones, ['cancelar'])
                : $acciones;

            foreach ($accionesModulo as $accion) {
                Permission::firstOrCreate(
                    ['name' => $accion . ' ' . $modulo, 'guard_name' => $guard],
                    ['modulo' => $modulo]
                );
            }
        }
    }
}