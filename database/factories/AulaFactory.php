<?php

namespace Database\Factories;

use App\Contexts\GestionEspacios\Infrastructure\Eloquent\AulaEloquentModel;
use App\Contexts\GestionEspacios\Infrastructure\Eloquent\RecintoEloquentModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AulaEloquentModel>
 */
class AulaFactory extends Factory
{
    protected $model = AulaEloquentModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'recinto_id' => RecintoEloquentModel::factory(),
            'nombre' => 'Aula '.fake()->unique()->numberBetween(100, 999),
            'piso' => (string) fake()->numberBetween(1, 4),
            'tipo' => 'Aula regular',
            'capacidad' => fake()->numberBetween(15, 45),
            'no_disponible_desde' => null,
        ];
    }

    /**
     * Indicate that the aula is un laboratorio de cómputo.
     */
    public function laboratorioComputo(): static
    {
        return $this->state(fn (array $attributes) => [
            'nombre' => 'Laboratorio de Cómputo '.fake()->unique()->numberBetween(1, 99),
            'tipo' => 'Laboratorio de cómputo',
        ]);
    }

    /**
     * Indicate that the aula is un laboratorio de ciencias.
     */
    public function laboratorioCiencias(): static
    {
        return $this->state(fn (array $attributes) => [
            'nombre' => 'Laboratorio de Ciencias '.fake()->unique()->numberBetween(1, 99),
            'tipo' => 'Laboratorio de ciencias',
        ]);
    }

    /**
     * Indicate that the aula is un laboratorio de idiomas.
     */
    public function laboratorioIdiomas(): static
    {
        return $this->state(fn (array $attributes) => [
            'nombre' => 'Laboratorio de Idiomas '.fake()->unique()->numberBetween(1, 99),
            'tipo' => 'Laboratorio de idiomas',
        ]);
    }

    /**
     * Indicate that the aula is un auditorio.
     */
    public function auditorio(): static
    {
        return $this->state(fn (array $attributes) => [
            'nombre' => 'Auditorio '.fake()->unique()->numberBetween(1, 99),
            'tipo' => 'Auditorio',
            'capacidad' => fake()->numberBetween(80, 200),
        ]);
    }

    /**
     * Indicate that the aula no está disponible a partir de una fecha.
     */
    public function noDisponible(): static
    {
        return $this->state(fn (array $attributes) => [
            'no_disponible_desde' => fake()->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
        ]);
    }
}
