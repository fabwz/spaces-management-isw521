<?php

namespace Database\Factories;

use App\Contexts\GestionEspacios\Infrastructure\Eloquent\EquipamientoEloquentModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EquipamientoEloquentModel>
 */
class EquipamientoFactory extends Factory
{
    protected $model = EquipamientoEloquentModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => fake()->unique()->words(2, true),
        ];
    }
}
