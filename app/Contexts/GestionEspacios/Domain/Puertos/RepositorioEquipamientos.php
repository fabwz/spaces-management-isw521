<?php

namespace App\Contexts\GestionEspacios\Domain\Puertos;

use App\Contexts\GestionEspacios\Domain\Entidades\Equipamiento;

interface RepositorioEquipamientos
{
    public function guardar(Equipamiento $equipamiento): Equipamiento;

    public function buscarPorId(int $id): ?Equipamiento;

    public function listar(): array;

    public function eliminar(int $id): void;
}
