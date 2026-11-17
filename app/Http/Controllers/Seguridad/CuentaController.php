<?php

namespace App\Http\Controllers\Seguridad;

use App\Http\Controllers\Controller;
use App\Models\Empleado;
use App\Rules\ContrasenaSegura;
use App\Services\Seguridad\CuentaService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CuentaController extends Controller
{
    public function __construct(private CuentaService $cuentaService)
    {
    }

    public function index(): View
    {
        return view('seguridad.cuentas.index', [
            'usuarios' => $this->cuentaService->listar(),
        ]);
    }

    public function create(): View
    {
        return view('seguridad.cuentas.form', [
            'usuario' => null,
            'roles' => $this->cuentaService->listarRoles(),
            'seleccionados' => old('roles', []),
            'empleados' => Empleado::orderBy('nombreEmpleado')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique(config('auth.providers.users.model'), 'email')],
            'password' => ['required', 'string', new ContrasenaSegura(), 'confirmed'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', Rule::exists(config('permission.table_names.roles'), 'name')],
            'empleado_id' => [
                Rule::requiredIf(fn () => $this->esRolEmpleado($request)),
                'nullable',
                'exists:empleados,id',
                Rule::unique(config('auth.providers.users.model'), 'empleado_id'),
            ],
        ], $this->mensajesEmpleado());

        $datos['empleado_id'] = $this->esRolEmpleado($request) ? $datos['empleado_id'] : null;

        $this->cuentaService->crear(
            Arr::only($datos, ['name', 'email', 'password', 'empleado_id']),
            $datos['roles'] ?? []
        );

        return redirect()->route('seguridad.cuentas.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit(string $cuenta): View
    {
        $usuario = $this->cuentaService->buscar($cuenta);

        return view('seguridad.cuentas.form', [
            'usuario' => $usuario,
            'roles' => $this->cuentaService->listarRoles(),
            'seleccionados' => old('roles', $usuario->rolesConInactivos->pluck('name')->all()),
            'empleados' => Empleado::orderBy('nombreEmpleado')->get(),
        ]);
    }

    public function update(Request $request, string $cuenta): RedirectResponse
    {
        $usuario = $this->cuentaService->buscar($cuenta);

        $datos = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique(config('auth.providers.users.model'), 'email')->ignore($usuario->getKey())],
            'password' => ['nullable', 'string', new ContrasenaSegura(), 'confirmed'],
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', Rule::exists(config('permission.table_names.roles'), 'name')],
            'empleado_id' => [
                Rule::requiredIf(fn () => $this->esRolEmpleado($request)),
                'nullable',
                'exists:empleados,id',
                Rule::unique(config('auth.providers.users.model'), 'empleado_id')->ignore($usuario->getKey()),
            ],
        ], $this->mensajesEmpleado());

        $datos['empleado_id'] = $this->esRolEmpleado($request) ? $datos['empleado_id'] : null;

        try {
            $this->cuentaService->actualizar(
                $usuario,
                Arr::only($datos, ['name', 'email', 'password', 'empleado_id']),
                $datos['roles'] ?? [],
                $request->user()
            );
        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('seguridad.cuentas.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(Request $request, string $cuenta): RedirectResponse
    {
        try {
            $this->cuentaService->eliminar($this->cuentaService->buscar($cuenta), $request->user());
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('seguridad.cuentas.index')->with('success', 'Usuario eliminado correctamente.');
    }

    private function esRolEmpleado(Request $request): bool
    {
        return in_array(config('seguridad.rol_empleado'), $request->input('roles', []), true);
    }

    private function mensajesEmpleado(): array
    {
        return [
            'empleado_id.required' => 'Selecciona el empleado al que pertenece esta cuenta.',
            'empleado_id.unique' => 'Ese empleado ya tiene una cuenta vinculada.',
        ];
    }
}