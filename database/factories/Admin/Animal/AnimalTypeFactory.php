<?php

namespace Database\Factories\Admin\Animal;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin\Animal\AnimalType>
 */
class AnimalTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['Bovino', 'Equino', 'Ovino', 'Caprino']),
        ];
    }
}