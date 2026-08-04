<?php

namespace App\Contexts\GestionEspacios\Domain\ValueObjects;

/**
 * Intervalo semiabierto [inicio, fin). Construcción en dos etapas: este VO
 * solo trae la guarda de constructor; feature/au-03-choque-espacio lo
 * extiende agregando seSolapaCon() en este mismo archivo.
 */
final class Intervalo
{
    private string $inicio;
    private string $fin;

    public function __construct(string $inicio, string $fin)
    {
        if ($inicio >= $fin) {
            throw new \InvalidArgumentException('El inicio del intervalo debe ser anterior al fin.');
        }

        $this->inicio = $inicio;
        $this->fin = $fin;
    }

    public function inicio(): string
    {
        return $this->inicio;
    }

    public function fin(): string
    {
        return $this->fin;
    }
}
