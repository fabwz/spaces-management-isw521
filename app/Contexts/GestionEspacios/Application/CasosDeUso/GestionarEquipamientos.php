<?php

namespace App\Contexts\GestionEspacios\Application\CasosDeUso;

use App\Contexts\GestionEspacios\Domain\Entidades\Equipamiento;
use App\Contexts\GestionEspacios\Domain\Puertos\RepositorioEquipamientos;

final class GestionarEquipamientos
{
    public function __construct(
        private readonly RepositorioEquipamientos $repositorioEquipamientos,
    ) {
    }

    public function crear(string $nombre): Equipamiento
    {
        $equipamiento = new Equipamiento(null, $nombre);

        return $this->repositorioEquipamientos->guardar($equipamiento);
    }

    public function listar(): array
    {
        return $this->repositorioEquipamientos->listar();
    }

    public function obtener(int $id): ?Equipamiento
    {
        return $this->repositorioEquipamientos->buscarPorId($id);
    }

    public function actualizar(int $id, string $nombre): Equipamiento
    {
        $equipamiento = new Equipamiento($id, $nombre);

        return $this->repositorioEquipamientos->guardar($equipamiento);
    }

    public function eliminar(int $id): void
    {
        $this->repositorioEquipamientos->eliminar($id);
    }
}
