<?php

namespace App\Contexts\GestionEspacios\Domain\Entidades;

use App\Contexts\GestionEspacios\Domain\Exceptions\TransicionEstadoInvalidaException;
use App\Contexts\GestionEspacios\Domain\ValueObjects\Intervalo;
use App\Contexts\GestionEspacios\Domain\ValueObjects\TipoUso;

final class ReservaPuntual
{
    private const ESTADOS_VALIDOS = ['Solicitada', 'Aprobada', 'Rechazada', 'Cancelada'];

    private string $estado;

    public function __construct(
        private ?int $id,
        private int $aulaId,
        private ?int $userId,
        private TipoUso $tipo,
        private ?string $solicitante,
        private string $motivo,
        private string $fechaInicio,
        private ?string $fechaFin,
        private ?array $diasSemana,
        private Intervalo $horario,
        string $estado,
    ) {
        if (! in_array($estado, self::ESTADOS_VALIDOS, true)) {
            throw new \InvalidArgumentException('Estado de reserva inválido: ' . $estado);
        }

        $this->estado = $estado;
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function aulaId(): int
    {
        return $this->aulaId;
    }

    public function userId(): ?int
    {
        return $this->userId;
    }

    public function tipo(): TipoUso
    {
        return $this->tipo;
    }

    public function solicitante(): ?string
    {
        return $this->solicitante;
    }

    public function motivo(): string
    {
        return $this->motivo;
    }

    public function fechaInicio(): string
    {
        return $this->fechaInicio;
    }

    public function fechaFin(): ?string
    {
        return $this->fechaFin;
    }

    public function diasSemana(): ?array
    {
        return $this->diasSemana;
    }

    public function horario(): Intervalo
    {
        return $this->horario;
    }

    public function estado(): string
    {
        return $this->estado;
    }

    public function aprobar(): void
    {
        if ($this->estado !== 'Solicitada') {
            throw TransicionEstadoInvalidaException::paraReserva($this->estado, 'Aprobada');
        }

        $this->estado = 'Aprobada';
    }

    public function rechazar(): void
    {
        if ($this->estado !== 'Solicitada') {
            throw TransicionEstadoInvalidaException::paraReserva($this->estado, 'Rechazada');
        }

        $this->estado = 'Rechazada';
    }

    public function cancelar(): void
    {
        if (! in_array($this->estado, ['Solicitada', 'Aprobada'], true)) {
            throw TransicionEstadoInvalidaException::paraReserva($this->estado, 'Cancelada');
        }

        $this->estado = 'Cancelada';
    }
}
