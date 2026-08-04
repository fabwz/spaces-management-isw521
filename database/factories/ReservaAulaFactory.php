<?php

namespace Database\Factories;

use App\Contexts\GestionEspacios\Infrastructure\Eloquent\AulaEloquentModel;
use App\Contexts\GestionEspacios\Infrastructure\Eloquent\ReservaAulaEloquentModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ReservaAulaEloquentModel>
 */
class ReservaAulaFactory extends Factory
{
    protected $model = ReservaAulaEloquentModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tipo = fake()->randomElement(['Reserva', 'Bloqueo administrativo']);
        $horaInicio = fake()->numberBetween(7, 18);

        return [
            'aula_id' => AulaEloquentModel::factory(),
            'user_id' => User::factory(),
            'tipo' => $tipo,
            'solicitante' => $tipo === 'Bloqueo administrativo' ? null : fake()->company(),
            'motivo' => fake()->sentence(),
            'fecha_inicio' => fake()->dateTimeBetween('now', '+2 months')->format('Y-m-d'),
            'fecha_fin' => null,
            'dias_semana' => null,
            'hora_inicio' => sprintf('%02d:00:00', $horaInicio),
            'hora_fin' => sprintf('%02d:00:00', $horaInicio + 1),
            'estado' => 'Solicitada',
        ];
    }

    /**
     * Indicate that the reserva is de tipo 'Reserva' (uso no académico regular).
     */
    public function reserva(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo' => 'Reserva',
            'solicitante' => fake()->company(),
        ]);
    }

    /**
     * Indicate that the reserva is un 'Bloqueo administrativo' (sin solicitante).
     */
    public function bloqueoAdministrativo(): static
    {
        return $this->state(fn (array $attributes) => [
            'tipo' => 'Bloqueo administrativo',
            'solicitante' => null,
        ]);
    }

    /**
     * Indicate that la reserva está en estado 'Aprobada'.
     */
    public function aprobada(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'Aprobada',
        ]);
    }

    /**
     * Indicate that la reserva está en estado 'Rechazada'.
     */
    public function rechazada(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'Rechazada',
        ]);
    }

    /**
     * Indicate that la reserva está en estado 'Cancelada'.
     */
    public function cancelada(): static
    {
        return $this->state(fn (array $attributes) => [
            'estado' => 'Cancelada',
        ]);
    }
}
