<?php

namespace App\Contexts\GestionEspacios\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActualizarEspacioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'recinto_id' => ['required', 'integer', 'exists:recintos,id'],
            'nombre' => ['required', 'string', 'max:30'],
            'piso' => ['nullable', 'string', 'max:10'],
            'tipo' => [
                'required',
                'string',
                'in:Aula regular,Laboratorio de cómputo,Laboratorio de ciencias,Laboratorio de idiomas,Auditorio,Otro',
            ],
            'capacidad' => ['nullable', 'integer', 'min:1'],
            'no_disponible_desde' => ['nullable', 'date'],
        ];
    }
}
