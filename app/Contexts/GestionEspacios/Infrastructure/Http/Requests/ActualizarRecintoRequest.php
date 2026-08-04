<?php

namespace App\Contexts\GestionEspacios\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActualizarRecintoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:80'],
            'es_propio' => ['required', 'boolean'],
            'activo' => ['required', 'boolean'],
        ];
    }
}
