<?php

namespace App\Contexts\GestionEspacios\Infrastructure\Http\Resources;

use App\Contexts\GestionEspacios\Domain\Entidades\Equipamiento;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read Equipamiento $resource
 */
class EquipamientoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id(),
            'nombre' => $this->resource->nombre(),
        ];
    }
}
