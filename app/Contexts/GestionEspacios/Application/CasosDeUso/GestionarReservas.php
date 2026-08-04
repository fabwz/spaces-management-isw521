<?php

namespace App\Contexts\GestionEspacios\Application\CasosDeUso;

use App\Contexts\GestionEspacios\Domain\Entidades\ReservaPuntual;
use App\Contexts\GestionEspacios\Domain\Puertos\RepositorioReservas;
use App\Contexts\GestionEspacios\Domain\ValueObjects\Intervalo;
use App\Contexts\GestionEspacios\Domain\ValueObjects\TipoUso;

final class GestionarReservas
{
    public function __construct(
        private readonly RepositorioReservas $repositorioReservas,
    ) {
    }

    public function crear(
        int $aulaId,
        ?int $userId,
        string $tipo,
        ?string $solicitante,
        string $motivo,
        string $fechaInicio,
        ?string $fechaFin,
        ?array $diasSemana,
        string $horaInicio,
        string $horaFin,
        string $estado,
    ): ReservaPuntual {
        $reserva = new ReservaPuntual(
            null,
            $aulaId,
            $userId,
            TipoUso::from($tipo),
            $solicitante,
            $motivo,
            $fechaInicio,
            $fechaFin,
            $diasSemana,
            new Intervalo($horaInicio, $horaFin),
            $estado,
        );

        return $this->repositorioReservas->guardar($reserva);
    }

    public function listar(): array
    {
        return $this->repositorioReservas->listar();
    }

    public function obtener(int $id): ?ReservaPuntual
    {
        return $this->repositorioReservas->buscarPorId($id);
    }

    public function actualizar(
        int $id,
        int $aulaId,
        ?int $userId,
        string $tipo,
        ?string $solicitante,
        string $motivo,
        string $fechaInicio,
        ?string $fechaFin,
        ?array $diasSemana,
        string $horaInicio,
        string $horaFin,
        string $estado,
    ): ReservaPuntual {
        $reserva = new ReservaPuntual(
            $id,
            $aulaId,
            $userId,
            TipoUso::from($tipo),
            $solicitante,
            $motivo,
            $fechaInicio,
            $fechaFin,
            $diasSemana,
            new Intervalo($horaInicio, $horaFin),
            $estado,
        );

        return $this->repositorioReservas->guardar($reserva);
    }

    public function eliminar(int $id): void
    {
        $this->repositorioReservas->eliminar($id);
    }

    public function aprobar(int $id): ReservaPuntual
    {
        $reserva = $this->repositorioReservas->buscarPorId($id);

        if ($reserva === null) {
            throw new \InvalidArgumentException("No existe una reserva con id {$id}.");
        }

        $reserva->aprobar();

        return $this->repositorioReservas->guardar($reserva);
    }

    public function rechazar(int $id): ReservaPuntual
    {
        $reserva = $this->repositorioReservas->buscarPorId($id);

        if ($reserva === null) {
            throw new \InvalidArgumentException("No existe una reserva con id {$id}.");
        }

        $reserva->rechazar();

        return $this->repositorioReservas->guardar($reserva);
    }

    public function cancelar(int $id): ReservaPuntual
    {
        $reserva = $this->repositorioReservas->buscarPorId($id);

        if ($reserva === null) {
            throw new \InvalidArgumentException("No existe una reserva con id {$id}.");
        }

        $reserva->cancelar();

        return $this->repositorioReservas->guardar($reserva);
    }
}
