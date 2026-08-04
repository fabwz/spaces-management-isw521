<?php

namespace App\Contexts\GestionEspacios\Infrastructure\Repositorios;

use App\Contexts\GestionEspacios\Domain\Entidades\Espacio;
use App\Contexts\GestionEspacios\Domain\Puertos\RepositorioEspacios;
use App\Contexts\GestionEspacios\Domain\ValueObjects\Capacidad;
use App\Contexts\GestionEspacios\Infrastructure\Eloquent\AulaEloquentModel;

final class RepositorioEspaciosEloquent implements RepositorioEspacios
{
    public function guardar(Espacio $espacio): Espacio
    {
        $modelo = $espacio->id() !== null
            ? AulaEloquentModel::findOrFail($espacio->id())
            : new AulaEloquentModel();

        $modelo->fill([
            'recinto_id' => $espacio->recintoId(),
            'nombre' => $espacio->nombre(),
            'piso' => $espacio->piso() !== null ? (string) $espacio->piso() : null,
            'tipo' => $espacio->tipo(),
            'capacidad' => $espacio->capacidad()?->valor(),
            'no_disponible_desde' => $espacio->noDisponibleDesde(),
        ]);

        $modelo->save();

        return $this->aEntidad($modelo);
    }

    public function buscarPorId(int $id): ?Espacio
    {
        $modelo = AulaEloquentModel::find($id);

        return $modelo !== null ? $this->aEntidad($modelo) : null;
    }

    public function listar(): array
    {
        return AulaEloquentModel::all()
            ->map(fn (AulaEloquentModel $modelo) => $this->aEntidad($modelo))
            ->all();
    }

    public function eliminar(int $id): void
    {
        AulaEloquentModel::whereKey($id)->delete();
    }

    private function aEntidad(AulaEloquentModel $modelo): Espacio
    {
        return new Espacio(
            $modelo->id,
            (int) $modelo->recinto_id,
            $modelo->nombre,
            $modelo->piso !== null ? (int) $modelo->piso : null,
            $modelo->tipo,
            $modelo->capacidad !== null ? new Capacidad($modelo->capacidad) : null,
            $modelo->no_disponible_desde?->format('Y-m-d'),
        );
    }
}
