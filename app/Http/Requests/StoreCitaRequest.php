<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCitaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Se ejecuta ANTES de validar: si quien crea la cita es un cliente,
     * se fuerzan sus datos propios sin importar lo que envíe el formulario.
     */
    protected function prepareForValidation(): void
    {
        $usuario = $this->user();

        if (! $usuario) {
            return;
        }

        if ($usuario->cliente_id !== null) {
            $this->merge([
                'cliente_id'   => (int) $usuario->cliente_id,
                'estado'       => 'pendiente',
                'fecha_inicio' => null,
                'fecha_fin'    => null,
            ]);

            return;
        }

        if ($usuario->hasRole(config('seguridad.rol_cliente'))) {
            abort(403, 'Tu cuenta no está vinculada a un cliente. Pide al administrador que la vincule.');
        }
    }

    public function rules(): array
    {
        return [
            'cliente_id' => ['required', 'exists:clientes,id'],
            'empleado_id' => [
                'required',
                Rule::exists('empleados', 'id')->where('disponibilidad', 'disponible'),
            ],
            'fecha_programada' => ['required', 'date'],
            'estado' => ['required', 'in:pendiente,confirmada,iniciada,finalizada'],
            'fecha_inicio' => ['nullable', 'date', 'required_if:estado,iniciada,finalizada'],
            'fecha_fin' => ['nullable', 'date', 'required_if:estado,finalizada', 'after:fecha_inicio'],
            'servicios' => ['required', 'array', 'min:1'],
            'servicios.*' => [
                Rule::exists('servicios', 'id')->where('estadoServicio', 'activo'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'empleado_id.exists' => 'El empleado seleccionado no está disponible. Elige otro empleado.',
            'servicios.required' => 'Debes seleccionar al menos un servicio para la cita.',
            'servicios.*.exists' => 'Uno de los servicios seleccionados ya no está disponible. Elige otro e intenta de nuevo.',
            'fecha_fin.after'    => 'La fecha/hora de finalización debe ser posterior a la de inicio.',
        ];
    }
}