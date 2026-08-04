<?php

namespace App\Contexts\GestionEspacios\Application\CasosDeUso;

use App\Contexts\GestionEspacios\Domain\Entidades\Recinto;
use App\Contexts\GestionEspacios\Domain\Puertos\RepositorioRecintos;

final class GestionarRecintos
{
    public function __construct(
        private readonly RepositorioRecintos $repositorioRecintos,
    ) {
    }

    public function crear(string $nombre, bool $esPropio, bool $activo): Recinto
    {
        $recinto = new Recinto(null, $nombre, $esPropio, $activo);

        return $this->repositorioRecintos->guardar($recinto);
    }

    public function listar(): array
    {
        return $this->repositorioRecintos->listar();
    }

    public function obtener(int $id): ?Recinto
    {
        return $this->repositorioRecintos->buscarPorId($id);
    }

    public function actualizar(int $id, string $nombre, bool $esPropio, bool $activo): Recinto
    {
        $recinto = new Recinto($id, $nombre, $esPropio, $activo);

        return $this->repositorioRecintos->guardar($recinto);
    }

    public function eliminar(int $id): void
    {
        $this->repositorioRecintos->eliminar($id);
    }
}
