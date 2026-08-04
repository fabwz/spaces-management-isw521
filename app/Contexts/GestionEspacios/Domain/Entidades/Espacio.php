<?php

namespace App\Contexts\GestionEspacios\Domain\Entidades;

use App\Contexts\GestionEspacios\Domain\ValueObjects\Capacidad;

final class Espacio
{
    public function __construct(
        private ?int $id,
        private int $recintoId,
        private string $nombre,
        private ?string $piso,
        private string $tipo,
        private ?Capacidad $capacidad,
        private ?string $noDisponibleDesde,
    ) {
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function recintoId(): int
    {
        return $this->recintoId;
    }

    public function nombre(): string
    {
        return $this->nombre;
    }

    public function piso(): ?string
    {
        return $this->piso;
    }

    public function tipo(): string
    {
        return $this->tipo;
    }

    public function capacidad(): ?Capacidad
    {
        return $this->capacidad;
    }

    public function noDisponibleDesde(): ?string
    {
        return $this->noDisponibleDesde;
    }
}
