<?php

namespace App\Contexts\GestionEspacios\Infrastructure\Repositorios;

use App\Contexts\GestionEspacios\Domain\Entidades\Recinto;
use App\Contexts\GestionEspacios\Domain\Puertos\RepositorioRecintos;
use App\Contexts\GestionEspacios\Infrastructure\Eloquent\RecintoEloquentModel;

final class RepositorioRecintosEloquent implements RepositorioRecintos
{
    public function guardar(Recinto $recinto): Recinto
    {
        $modelo = $recinto->id() !== null
            ? RecintoEloquentModel::findOrFail($recinto->id())
            : new RecintoEloquentModel();

        $modelo->fill([
            'nombre' => $recinto->nombre(),
            'es_propio' => $recinto->esPropio(),
            'activo' => $recinto->activo(),
        ]);

        $modelo->save();

        return $this->aEntidad($modelo);
    }

    public function buscarPorId(int $id): ?Recinto
    {
        $modelo = RecintoEloquentModel::find($id);

        return $modelo !== null ? $this->aEntidad($modelo) : null;
    }

    public function listar(): array
    {
        return RecintoEloquentModel::all()
            ->map(fn (RecintoEloquentModel $modelo) => $this->aEntidad($modelo))
            ->all();
    }

    public function eliminar(int $id): void
    {
        RecintoEloquentModel::whereKey($id)->delete();
    }

    private function aEntidad(RecintoEloquentModel $modelo): Recinto
    {
        return new Recinto(
            $modelo->id,
            $modelo->nombre,
            $modelo->es_propio,
            $modelo->activo,
        );
    }
}
