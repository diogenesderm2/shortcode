<?php

namespace Database\Factories\Admin\Owner;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class OwnerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        return [
            'registration' => $this->faker->unique()->numberBetween(1000000000, 9999999999),
            'user_id' => User::factory(), // ou definido manualmente no seeder
            'name' => $this->faker->name(),
            'rg' => $this->faker->numerify('#########'),
            'cpf' => $this->faker->numerify('###########'),
            'cnpj' => $this->faker->numerify('##############'),
            'property' => $this->faker->sentence(),
            'image' => 'https://placehold.co/80x80',
        ];
    }
}
