<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ContrasenaSegura implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            return;
        }

        if (mb_strlen($value) < 8) {
            $fail('La contraseña debe tener al menos 8 caracteres.');
            return;
        }

        if (! preg_match('/\p{Lu}/u', $value) || ! preg_match('/\p{Ll}/u', $value)) {
            $fail('La contraseña debe incluir al menos una letra mayúscula y una minúscula.');
            return;
        }

        if (! preg_match('/\d/', $value)) {
            $fail('La contraseña debe incluir al menos un número.');
        }
    }
}