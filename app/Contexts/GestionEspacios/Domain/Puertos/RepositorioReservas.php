<?php

namespace App\Contexts\GestionEspacios\Domain\Puertos;

use App\Contexts\GestionEspacios\Domain\Entidades\ReservaPuntual;

interface RepositorioReservas
{
    public function guardar(ReservaPuntual $reserva): ReservaPuntual;

    public function buscarPorId(int $id): ?ReservaPuntual;

    public function listar(): array;

    public function eliminar(int $id): void;
}
