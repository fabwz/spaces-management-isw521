<?php

namespace App\Contexts\GestionEspacios\Domain\Entidades;

final class Equipamiento
{
    private string $nombre;

    public function __construct(
        private ?int $id,
        string $nombre,
    ) {
        if (trim($nombre) === '') {
            throw new \InvalidArgumentException('El nombre del equipamiento no puede estar vacío.');
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
}
