<?php

namespace App\Contexts\GestionEspacios\Domain\Puertos;

use App\Contexts\GestionEspacios\Domain\Entidades\Espacio;

interface RepositorioEspacios
{
    public function guardar(Espacio $espacio): Espacio;

    public function buscarPorId(int $id): ?Espacio;

    public function listar(): array;

    public function eliminar(int $id): void;
}
