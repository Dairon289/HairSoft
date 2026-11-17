<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServicioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nombreServicio'      => ['required', 'string', 'max:150'],
            'duracionServicio'    => ['required'],
            'descripcionServicio' => ['nullable', 'string', 'max:500'],
            'precioServicio'      => ['required', 'numeric', 'min:0'],
            'estadoServicio'      => ['required', 'in:activo,inactivo'],
        ];
    }

    /**
     * Nombres de atributos personalizados para mensajes de error.
     */
    public function attributes(): array
    {
        return [
            'nombreServicio'      => 'nombre del servicio',
            'duracionServicio'    => 'duración del servicio',
            'descripcionServicio' => 'descripción del servicio',
            'precioServicio'      => 'precio del servicio',
            'estadoServicio'      => 'estado del servicio',
        ];
    }
}
