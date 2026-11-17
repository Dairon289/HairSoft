<?php

namespace App\Http\Requests;

use App\Rules\ContrasenaSegura;
use Illuminate\Foundation\Http\FormRequest;

class StoreClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'   => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'correo'   => ['required', 'email', 'max:255', 'unique:clientes,correo', 'unique:users,email'],
            'telefono' => ['required', 'string', 'max:255'],
            'rol'      => ['required', 'in:cliente,administrador'],
            'estado'   => ['required', 'in:activo,inactivo'],
            'password' => ['required', 'string', new ContrasenaSegura()],
        ];
    }
}
