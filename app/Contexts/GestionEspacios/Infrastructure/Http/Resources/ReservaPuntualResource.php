<?php

namespace App\Contexts\GestionEspacios\Infrastructure\Http\Resources;

use App\Contexts\GestionEspacios\Domain\Entidades\ReservaPuntual;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read ReservaPuntual $resource
 */
class ReservaPuntualResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id(),
            'aula_id' => $this->resource->aulaId(),
            'user_id' => $this->resource->userId(),
            'tipo' => $this->resource->tipo()->value,
            'solicitante' => $this->resource->solicitante(),
            'motivo' => $this->resource->motivo(),
            'fecha_inicio' => $this->resource->fechaInicio(),
            'fecha_fin' => $this->resource->fechaFin(),
            'dias_semana' => $this->resource->diasSemana(),
            'hora_inicio' => $this->resource->horario()->inicio(),
            'hora_fin' => $this->resource->horario()->fin(),
            'estado' => $this->resource->estado(),
        ];
    }
}
