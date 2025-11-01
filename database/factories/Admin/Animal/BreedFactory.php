<?php

namespace Database\Factories\Admin\Animal;

use App\Models\Admin\Animal\AnimalType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin\Animal\Breed>
 */
class BreedFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'animal_type_id' => AnimalType::factory(),
        ];
    }
}