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
            'user_id' => User::factory(),
            'user_registered' => null, // Pode ser definido posteriormente se necessário
            'user_updated' => null,
            'user_financial' => null,
            'user_representative' => null,
            'name' => $this->faker->name(),
            'address' => $this->faker->streetAddress(),
            'adress_complement' => $this->faker->secondaryAddress(),
            'adress_number' => $this->faker->buildingNumber(),
            'district' => $this->faker->citySuffix(),
            'city' => $this->faker->city(),
            'state' => $this->faker->stateAbbr(),
            'zip_code' => $this->faker->postcode(),
            'rg' => $this->faker->numerify('#########'),
            'cpf' => $this->faker->numerify('###########'),
            'cnpj' => $this->faker->numerify('##############'),
            'state_registration' => $this->faker->numerify('##########'),
            'property' => $this->faker->sentence(),
            'representative_percentage' => $this->faker->numberBetween(0, 100),
            'comments' => $this->faker->paragraph(),
            'image' => 'https://placehold.co/80x80',
            'reteste_without_payment' => 0,
            'reteste_without_release' => 0,
        ];
    }
}
