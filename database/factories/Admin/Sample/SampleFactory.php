<?php

namespace Database\Factories\Admin\Sample;

use App\Models\Admin\Owner\Owner;
use App\Models\Admin\Animal\Animal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin\Sample\Sample>
 */
class SampleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sample_code' => $this->faker->unique()->numerify('DNA####'),
            'owner_id' => Owner::factory(),
            'father_id' => Animal::factory(),
            'mother_id' => Animal::factory(),
            'child_id' => Animal::factory(),
            'sample_type' => $this->faker->randomElement(['sangue', 'pelo', 'saliva']),
            'collection_date' => $this->faker->date(),
            'observations' => $this->faker->optional()->sentence(),
            'status' => $this->faker->randomElement(['pendente', 'processando', 'concluido']),
        ];
    }
}