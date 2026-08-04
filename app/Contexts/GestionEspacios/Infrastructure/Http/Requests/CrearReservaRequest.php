<?php

namespace App\Contexts\GestionEspacios\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CrearReservaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'aula_id' => ['required', 'integer', 'exists:aulas,id'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'tipo' => ['required', 'string', 'in:Reserva,Bloqueo administrativo'],
            'solicitante' => ['nullable', 'string', 'max:120'],
            'motivo' => ['required', 'string', 'max:255'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['nullable', 'date'],
            'dias_semana' => ['nullable', 'array'],
            'dias_semana.*' => ['string', 'in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado,Domingo'],
            'hora_inicio' => ['required', 'date_format:H:i'],
            'hora_fin' => ['required', 'date_format:H:i'],
            'estado' => ['required', 'string', 'in:Solicitada,Aprobada,Rechazada,Cancelada'],
        ];
    }
}
