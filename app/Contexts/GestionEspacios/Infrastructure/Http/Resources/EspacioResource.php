<?php

namespace App\Contexts\GestionEspacios\Infrastructure\Http\Resources;

use App\Contexts\GestionEspacios\Domain\Entidades\Espacio;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read Espacio $resource
 */
class EspacioResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id(),
            'recinto_id' => $this->resource->recintoId(),
            'nombre' => $this->resource->nombre(),
            'piso' => $this->resource->piso(),
            'tipo' => $this->resource->tipo(),
            'capacidad' => $this->resource->capacidad()?->valor(),
            'no_disponible_desde' => $this->resource->noDisponibleDesde(),
        ];
    }
}
