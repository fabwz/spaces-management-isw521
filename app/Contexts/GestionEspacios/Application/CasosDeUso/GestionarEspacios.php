<?php

namespace App\Contexts\GestionEspacios\Application\CasosDeUso;

use App\Contexts\GestionEspacios\Domain\Entidades\Espacio;
use App\Contexts\GestionEspacios\Domain\Puertos\RepositorioEspacios;
use App\Contexts\GestionEspacios\Domain\ValueObjects\Capacidad;
use App\Contexts\GestionEspacios\Infrastructure\Eloquent\AulaEloquentModel;

final class GestionarEspacios
{
    public function __construct(
        private readonly RepositorioEspacios $repositorioEspacios,
    ) {
    }

    public function crear(
        int $recintoId,
        string $nombre,
        ?int $piso,
        string $tipo,
        ?int $capacidad,
        ?string $noDisponibleDesde,
    ): Espacio {
        $espacio = new Espacio(
            null,
            $recintoId,
            $nombre,
            $piso,
            $tipo,
            $capacidad !== null ? new Capacidad($capacidad) : null,
            $noDisponibleDesde,
        );

        return $this->repositorioEspacios->guardar($espacio);
    }

    public function listar(): array
    {
        return $this->repositorioEspacios->listar();
    }

    public function obtener(int $id): ?Espacio
    {
        return $this->repositorioEspacios->buscarPorId($id);
    }

    public function actualizar(
        int $id,
        int $recintoId,
        string $nombre,
        ?int $piso,
        string $tipo,
        ?int $capacidad,
        ?string $noDisponibleDesde,
    ): Espacio {
        $espacio = new Espacio(
            $id,
            $recintoId,
            $nombre,
            $piso,
            $tipo,
            $capacidad !== null ? new Capacidad($capacidad) : null,
            $noDisponibleDesde,
        );

        return $this->repositorioEspacios->guardar($espacio);
    }

    public function eliminar(int $id): void
    {
        $this->repositorioEspacios->eliminar($id);
    }

    public function asignarEquipamiento(int $espacioId, int $equipamientoId, int $cantidad): void
    {
        $modelo = AulaEloquentModel::findOrFail($espacioId);
        $modelo->equipamientos()->syncWithoutDetaching([
            $equipamientoId => ['cantidad' => $cantidad],
        ]);
    }

    public function quitarEquipamiento(int $espacioId, int $equipamientoId): void
    {
        $modelo = AulaEloquentModel::findOrFail($espacioId);
        $modelo->equipamientos()->detach($equipamientoId);
    }
}
