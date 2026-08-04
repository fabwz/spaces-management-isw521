<?php

namespace App\Contexts\GestionEspacios\Infrastructure\Repositorios;

use App\Contexts\GestionEspacios\Domain\Entidades\Equipamiento;
use App\Contexts\GestionEspacios\Domain\Puertos\RepositorioEquipamientos;
use App\Contexts\GestionEspacios\Infrastructure\Eloquent\EquipamientoEloquentModel;

final class RepositorioEquipamientosEloquent implements RepositorioEquipamientos
{
    public function guardar(Equipamiento $equipamiento): Equipamiento
    {
        $modelo = $equipamiento->id() !== null
            ? EquipamientoEloquentModel::findOrFail($equipamiento->id())
            : new EquipamientoEloquentModel();

        $modelo->fill([
            'nombre' => $equipamiento->nombre(),
        ]);

        $modelo->save();

        return $this->aEntidad($modelo);
    }

    public function buscarPorId(int $id): ?Equipamiento
    {
        $modelo = EquipamientoEloquentModel::find($id);

        return $modelo !== null ? $this->aEntidad($modelo) : null;
    }

    public function listar(): array
    {
        return EquipamientoEloquentModel::all()
            ->map(fn (EquipamientoEloquentModel $modelo) => $this->aEntidad($modelo))
            ->all();
    }

    public function eliminar(int $id): void
    {
        EquipamientoEloquentModel::whereKey($id)->delete();
    }

    private function aEntidad(EquipamientoEloquentModel $modelo): Equipamiento
    {
        return new Equipamiento(
            $modelo->id,
            $modelo->nombre,
        );
    }
}
