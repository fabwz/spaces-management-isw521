<?php

namespace App\Contexts\GestionEspacios\Infrastructure\Repositorios;

use App\Contexts\GestionEspacios\Domain\Entidades\ReservaPuntual;
use App\Contexts\GestionEspacios\Domain\Puertos\RepositorioReservas;
use App\Contexts\GestionEspacios\Domain\ValueObjects\Intervalo;
use App\Contexts\GestionEspacios\Domain\ValueObjects\TipoUso;
use App\Contexts\GestionEspacios\Infrastructure\Eloquent\ReservaAulaEloquentModel;

final class RepositorioReservasEloquent implements RepositorioReservas
{
    public function guardar(ReservaPuntual $reserva): ReservaPuntual
    {
        $modelo = $reserva->id() !== null
            ? ReservaAulaEloquentModel::findOrFail($reserva->id())
            : new ReservaAulaEloquentModel();

        $modelo->fill([
            'aula_id' => $reserva->aulaId(),
            'user_id' => $reserva->userId(),
            'tipo' => $reserva->tipo()->value,
            'solicitante' => $reserva->solicitante(),
            'motivo' => $reserva->motivo(),
            'fecha_inicio' => $reserva->fechaInicio(),
            'fecha_fin' => $reserva->fechaFin(),
            'dias_semana' => $reserva->diasSemana() !== null
                ? implode(',', $reserva->diasSemana())
                : null,
            'hora_inicio' => $reserva->horario()->inicio(),
            'hora_fin' => $reserva->horario()->fin(),
            'estado' => $reserva->estado(),
        ]);

        $modelo->save();

        return $this->aEntidad($modelo);
    }

    public function buscarPorId(int $id): ?ReservaPuntual
    {
        $modelo = ReservaAulaEloquentModel::find($id);

        return $modelo !== null ? $this->aEntidad($modelo) : null;
    }

    public function listar(): array
    {
        return ReservaAulaEloquentModel::all()
            ->map(fn (ReservaAulaEloquentModel $modelo) => $this->aEntidad($modelo))
            ->all();
    }

    public function eliminar(int $id): void
    {
        ReservaAulaEloquentModel::whereKey($id)->delete();
    }

    private function aEntidad(ReservaAulaEloquentModel $modelo): ReservaPuntual
    {
        return new ReservaPuntual(
            $modelo->id,
            $modelo->aula_id,
            $modelo->user_id,
            TipoUso::from($modelo->tipo),
            $modelo->solicitante,
            $modelo->motivo,
            $modelo->fecha_inicio->format('Y-m-d'),
            $modelo->fecha_fin?->format('Y-m-d'),
            $modelo->dias_semana !== null && $modelo->dias_semana !== ''
                ? explode(',', $modelo->dias_semana)
                : null,
            new Intervalo(
                substr($modelo->hora_inicio, 0, 5),
                substr($modelo->hora_fin, 0, 5),
            ),
            $modelo->estado,
        );
    }
}
