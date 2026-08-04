<?php

namespace Database\Factories;

use App\Contexts\GestionEspacios\Infrastructure\Eloquent\RecintoEloquentModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RecintoEloquentModel>
 */
class RecintoFactory extends Factory
{
    protected $model = RecintoEloquentModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => fake()->unique()->company().' - '.fake()->city(),
            'es_propio' => true,
            'activo' => true,
        ];
    }

    /**
     * Indicate that the recinto is alquilado/convenio (no propio).
     */
    public function alquilado(): static
    {
        return $this->state(fn (array $attributes) => [
            'es_propio' => false,
        ]);
    }

    /**
     * Indicate that the recinto is inactivo.
     */
    public function inactivo(): static
    {
        return $this->state(fn (array $attributes) => [
            'activo' => false,
        ]);
    }
}
