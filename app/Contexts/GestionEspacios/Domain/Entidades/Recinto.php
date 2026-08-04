<?php

namespace App\Contexts\GestionEspacios\Domain\Entidades;

final class Recinto
{
    private string $nombre;

    public function __construct(
        private ?int $id,
        string $nombre,
        private bool $esPropio,
        private bool $activo,
    ) {
        if (trim($nombre) === '') {
            throw new \InvalidArgumentException('El nombre del recinto no puede estar vacío.');
        }

        $this->nombre = $nombre;
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function nombre(): string
    {
        return $this->nombre;
    }

    public function esPropio(): bool
    {
        return $this->esPropio;
    }

    public function activo(): bool
    {
        return $this->activo;
    }
}
