<?php

namespace App\Contexts\GestionEspacios\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AsignarEquipamientoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'equipamiento_id' => ['required', 'integer', 'exists:equipamientos,id'],
            'cantidad' => ['required', 'integer', 'min:1'],
        ];
    }
}
