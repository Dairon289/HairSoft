<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\SeguridadSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PerfilTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(SeguridadSeeder::class);
    }

    /**
     * Un invitado no puede entrar a la edición de perfil.
     */
    public function test_invitado_no_puede_acceder_al_perfil(): void
    {
        $response = $this->get('/perfil');

        $response->assertRedirect('/login');
    }

    /**
     * Un usuario autenticado puede ver su perfil.
     */
    public function test_usuario_puede_ver_su_pantalla_de_perfil(): void
    {
        $usuario = User::factory()->create([
            'name'  => 'Carlos Barbero',
            'email' => 'carlos@hairsoft.com',
        ]);

        $response = $this->actingAs($usuario)->get('/perfil');

        $response->assertStatus(200);
        $response->assertSee('Mi Perfil');
        $response->assertSee('Carlos Barbero');
    }

    /**
     * Un usuario puede actualizar su información básica.
     */
    public function test_usuario_puede_actualizar_su_nombre_y_correo(): void
    {
        $usuario = User::factory()->create([
            'name'  => 'Nombre Antiguo',
            'email' => 'antiguo@hairsoft.com',
        ]);

        $response = $this->actingAs($usuario)->patch('/perfil', [
            'name'  => 'Nombre Nuevo',
            'email' => 'nuevo@hairsoft.com',
        ]);

        $response->assertRedirect(route('profile.edit'));
        $this->assertDatabaseHas('users', [
            'id'    => $usuario->id,
            'name'  => 'Nombre Nuevo',
            'email' => 'nuevo@hairsoft.com',
        ]);
    }
}
