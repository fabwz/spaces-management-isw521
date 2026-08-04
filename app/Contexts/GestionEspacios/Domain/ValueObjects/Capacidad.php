<?php

namespace App\Contexts\GestionEspacios\Domain\ValueObjects;

final class Capacidad
{
    private int $valor;

    public function __construct(int $valor)
    {
        if ($valor <= 0) {
            throw new \InvalidArgumentException('La capacidad debe ser un entero positivo.');
        }

        $this->valor = $valor;
    }

    public function valor(): int
    {
        return $this->valor;
    }
}
