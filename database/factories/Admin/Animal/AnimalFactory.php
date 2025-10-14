<?php

namespace Database\Factories\Admin\Animal;

use App\Models\Admin\Owner\Owner;
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
            'name' => $this->faker->firstName(),
            'rg' => $this->faker->unique()->numerify('RG######'),
            'birth_date' => $this->faker->date(),
            'sex' => $this->faker->randomElement(['macho', 'femea']),
            'owner_id' => Owner::factory(),
        ];
    }
}