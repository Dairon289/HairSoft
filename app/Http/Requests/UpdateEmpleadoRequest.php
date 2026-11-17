<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmpleadoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $empleadoId = (int) $this->route('empleado');

        return [
            'nombreEmpleado'    => ['required', 'string', 'max:100'],
            'apellidoEmpleado'  => ['required', 'string', 'max:100'],
            'correoEmpleado'    => [
                'required',
                'email',
                'max:150',
                Rule::unique('empleados', 'correoEmpleado')->ignore($empleadoId),
            ],
            'telefonoEmpleado'  => ['required', 'string', 'max:20'],
            'fechaIngreso'      => ['required', 'date'],
            'horaIngreso'       => ['required'],
            'horaSalida'        => ['required', 'after:horaIngreso'],
            'disponibilidad'    => ['required', 'in:disponible,no disponible'],
            'estadoEmpleado'    => ['required', 'in:activo,inactivo'],
            'servicios'         => ['required', 'array', 'min:1'],
            'servicios.*'       => ['exists:servicios,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'servicios.required' => 'Selecciona al menos un servicio que ofrece el empleado.',
            'servicios.min'      => 'Selecciona al menos un servicio que ofrece el empleado.',
            'servicios.*.exists' => 'Uno de los servicios seleccionados no existe.',
            'horaSalida.after'   => 'La hora de salida debe ser posterior a la hora de ingreso.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nombreEmpleado'   => 'nombre del empleado',
            'apellidoEmpleado' => 'apellido del empleado',
            'correoEmpleado'   => 'correo electrónico',
            'telefonoEmpleado' => 'teléfono',
            'fechaIngreso'     => 'fecha de ingreso',
            'horaIngreso'      => 'hora de ingreso',
            'horaSalida'       => 'hora de salida',
            'disponibilidad'   => 'disponibilidad',
            'estadoEmpleado'   => 'estado del empleado',
        ];
    }
}
