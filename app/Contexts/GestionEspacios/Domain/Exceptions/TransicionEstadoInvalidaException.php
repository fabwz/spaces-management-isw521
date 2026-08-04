<?php

namespace App\Contexts\GestionEspacios\Domain\Exceptions;

final class TransicionEstadoInvalidaException extends \DomainException
{
    public static function paraReserva(string $estadoActual, string $estadoDestino): self
    {
        return new self("No se puede pasar una reserva de estado '{$estadoActual}' a '{$estadoDestino}'.");
    }
}
