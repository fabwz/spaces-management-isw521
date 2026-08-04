<?php

namespace App\Contexts\GestionEspacios\Infrastructure\Http\Resources;

use App\Contexts\GestionEspacios\Domain\Entidades\Recinto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read Recinto $resource
 */
class RecintoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id(),
            'nombre' => $this->resource->nombre(),
            'es_propio' => $this->resource->esPropio(),
            'activo' => $this->resource->activo(),
        ];
    }
}
