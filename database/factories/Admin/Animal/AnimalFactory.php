<?php

namespace Database\Factories\Admin\Animal;

use App\Models\Admin\Animal\AnimalType;
use App\Models\Admin\Animal\Breed;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin\Animal\Animal>
 */
class AnimalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'old_id' => $this->faker->optional()->randomNumber(6),
            'animal_type' => AnimalType::inRandomOrder()->first()?->id ?? 1,
            'breed_id' => Breed::inRandomOrder()->first()?->id ?? 1,
            'protocol' => $this->faker->optional()->numerify('PROT####'),
            'name' => $this->faker->firstName(),
            'register' => $this->faker->unique()->numerify('RG######'),
            'genre' => $this->faker->randomElement([0, 1, 2]),
            'birth' => $this->faker->date(),
        ];
    }
}