<?php

namespace App\Contexts\GestionEspacios\Domain\ValueObjects;

enum TipoUso: string
{
    case Reserva = 'Reserva';
    case BloqueoAdministrativo = 'Bloqueo administrativo';
}
