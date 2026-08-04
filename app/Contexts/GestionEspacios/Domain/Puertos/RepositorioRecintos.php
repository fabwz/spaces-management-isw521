<?php

namespace App\Contexts\GestionEspacios\Domain\Puertos;

use App\Contexts\GestionEspacios\Domain\Entidades\Recinto;

interface RepositorioRecintos
{
    public function guardar(Recinto $recinto): Recinto;

    public function buscarPorId(int $id): ?Recinto;

    public function listar(): array;

    public function eliminar(int $id): void;
}
