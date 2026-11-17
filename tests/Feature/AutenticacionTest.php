<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\SeguridadSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AutenticacionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(SeguridadSeeder::class);
    }

    /**
     * La pantalla de login carga correctamente.
     */
    public function test_la_pantalla_de_login_es_accesible(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Iniciar sesión');
        $response->assertSee('HairSoft');
    }

    /**
     * Un usuario administrador puede iniciar sesión y es redirigido al dashboard de admin.
     */
    public function test_usuario_administrador_inicia_sesion_correctamente(): void
    {
        $adminRole = Role::findByName('administrador', config('seguridad.guard'));

        $usuario = User::factory()->create([
            'email'    => 'admin@hairsoft.com',
            'password' => bcrypt('Password123*'),
        ]);
        $usuario->assignRole($adminRole);

        $response = $this->post('/login', [
            'email'    => 'admin@hairsoft.com',
            'password' => 'Password123*',
        ]);

        $this->assertAuthenticatedAs($usuario);
        $response->assertRedirect(route('admin.dashboard'));
    }

    /**
     * Un usuario con credenciales incorrectas no puede iniciar sesión.
     */
    public function test_login_falla_con_credenciales_invalidas(): void
    {
        $response = $this->post('/login', [
            'email'    => 'noexiste@correo.com',
            'password' => 'password_errado',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    /**
     * Un usuario autenticado puede cerrar sesión.
     */
    public function test_usuario_puede_cerrar_sesion(): void
    {
        $usuario = User::factory()->create();

        $response = $this->actingAs($usuario)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect(route('login.post'));
    }
}
