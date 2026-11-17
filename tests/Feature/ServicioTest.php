<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\SeguridadSeeder;
use Database\Seeders\ServicioSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ServicioTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([SeguridadSeeder::class, ServicioSeeder::class]);

        $adminRole = Role::findByName('administrador', config('seguridad.guard'));
        $this->admin = User::factory()->create([
            'email' => 'admin_test@hairsoft.com',
        ]);
        $this->admin->assignRole($adminRole);
    }

    /**
     * Un usuario sin autenticar no puede ver el listado de servicios.
     */
    public function test_invitado_es_redirigido_al_login_al_intentar_ver_servicios(): void
    {
        $response = $this->get('/servicio');

        $response->assertRedirect('/login');
    }

    /**
     * Un administrador autenticado puede ver el listado de servicios.
     */
    public function test_administrador_puede_listar_servicios(): void
    {
        $response = $this->actingAs($this->admin)->get('/servicio');

        $response->assertStatus(200);
        $response->assertSee('Corte de cabello');
    }

    /**
     * Un administrador puede registrar un nuevo servicio mediante el Form Request.
     */
    public function test_administrador_puede_crear_servicio(): void
    {
        $nuevoServicio = [
            'nombreServicio'      => 'Afeitado Clásico a Navaja',
            'duracionServicio'    => '00:30',
            'descripcionServicio' => 'Afeitado tradicional con toalla caliente',
            'precioServicio'      => 25000,
            'estadoServicio'      => 'activo',
        ];

        $response = $this->actingAs($this->admin)->post('/servicio', $nuevoServicio);

        $response->assertRedirect(route('servicio.index'));
        $this->assertDatabaseHas('servicios', [
            'nombreServicio' => 'Afeitado Clásico a Navaja',
            'precioServicio' => 25000,
        ]);
    }

    /**
     * La validación falla si faltan campos obligatorios.
     */
    public function test_creacion_de_servicio_falla_si_faltan_campos_requeridos(): void
    {
        $response = $this->actingAs($this->admin)->post('/servicio', [
            'nombreServicio' => '',
            'precioServicio' => 'invalido',
        ]);

        $response->assertSessionHasErrors(['nombreServicio', 'precioServicio', 'duracionServicio', 'estadoServicio']);
    }
}
