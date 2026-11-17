<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Muestra la vista de edición de perfil.
     */
    public function edit(Request $request): View
    {
        $usuario = $request->user();
        $empleado = $usuario->empleado;

        return view('profile.edit', compact('usuario', 'empleado'));
    }

    /**
     * Actualiza la información de perfil (básica y de empleado si aplica).
     */
    public function update(Request $request): RedirectResponse
    {
        $usuario = $request->user();

        $datos = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($usuario->id),
            ],
            // Campos adicionales si el usuario tiene un registro de empleado vinculado
            'telefono'    => ['nullable', 'string', 'max:20'],
            'especialidad' => ['nullable', 'string', 'max:100'],
        ]);

        $usuario->name = $datos['name'];
        $usuario->email = $datos['email'];
        $usuario->save();

        // Si tiene empleado vinculado, actualizamos sus datos de contacto/especialidad
        if ($usuario->empleado) {
            $empleado = $usuario->empleado;
            if (isset($datos['telefono'])) {
                $empleado->telefonoEmpleado = $datos['telefono'];
            }
            if (isset($datos['especialidad'])) {
                $empleado->especialidad = $datos['especialidad'];
            }
            $empleado->save();
        }

        return redirect()->route('profile.edit')->with('success', 'Perfil actualizado correctamente.');
    }

    /**
     * Actualiza la contraseña del usuario autenticado.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $usuario = $request->user();

        $validados = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::defaults()],
        ], [
            'current_password.current_password' => 'La contraseña actual no es correcta.',
            'password.confirmed'                => 'La confirmación de la nueva contraseña no coincide.',
        ]);

        $usuario->update([
            'password' => Hash::make($validados['password']),
        ]);

        return redirect()->route('profile.edit')->with('success_password', 'Contraseña actualizada con éxito.');
    }
}
