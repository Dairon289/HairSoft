<?php

namespace App\Http\Controllers\Seguridad;

use App\Http\Controllers\Controller;
use App\Services\Seguridad\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function showLoginForm(): View
    {
        return view('seguridad.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credenciales = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $autenticado = $this->authService->autenticar(
            $credenciales['email'],
            $credenciales['password'],
            $request->boolean('remember')
        );

        if (! $autenticado) {
            return back()
                ->withErrors(['email' => 'Las credenciales no coinciden con nuestros registros.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        $usuario = auth()->user();

        $destino = match (true) {
            $usuario->hasRole('administrador')                          => route('admin.dashboard'),
            $usuario->hasRole('cliente')                                => route('cliente.dashboard'),
            $usuario->hasRole(config('seguridad.rol_empleado', 'empleado')) => route('agenda.index'),
            default                                                     => route('welcome'),
        };

        return redirect()->intended($destino);
    }

    public function showRegisterForm(): View
    {
        return view('seguridad.register');
    }

    public function register(Request $request, \App\Services\ClienteService $clienteService): RedirectResponse
    {
        $datos = $request->validate([
            'nombre'   => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'correo'   => ['required', 'email', 'max:255', 'unique:clientes,correo', 'unique:users,email'],
            'telefono' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'confirmed', new \App\Rules\ContrasenaSegura()],
        ]);

        $datos['rol'] = 'cliente';
        $datos['estado'] = 'activo';

        $clienteService->guardar($datos);

        // Autenticar automáticamente al nuevo cliente
        $this->authService->autenticar($datos['correo'], $request->input('password'));
        $request->session()->regenerate();

        return redirect()->route('cliente.dashboard')->with('success', '¡Bienvenido a HairSoft! Tu cuenta ha sido creada exitosamente.');
    }

    public function logout(Request $request): RedirectResponse
    {
        $this->authService->cerrarSesion();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.post');
    }
}
