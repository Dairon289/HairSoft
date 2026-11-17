<?php

namespace App\Http\Requests;

use App\Rules\ContrasenaSegura;
use App\Services\ClienteService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(ClienteService $clienteService): array
    {
        $id = (int) $this->route('cliente');
        $cliente = $clienteService->buscarporid($id);
        $userId = $cliente?->usuario?->id;

        return [
            'nombre'   => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'correo'   => [
                'required',
                'email',
                'max:255',
                Rule::unique('clientes', 'correo')->ignore($id),
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'telefono' => ['required', 'string', 'max:255'],
            'rol'      => ['required', 'in:cliente,administrador'],
            'estado'   => ['required', 'in:activo,inactivo'],
            'password' => ['nullable', 'string', new ContrasenaSegura()],
        ];
    }
}
