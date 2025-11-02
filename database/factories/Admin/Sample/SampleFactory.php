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
        // IDs válidos baseados nos seeders
        $validExamIds = [1, 2, 5, 6, 7, 8, 15];
        $validBillingIds = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];
        $validSampleTypeIds = [1, 2, 3, 4, 5];

        return [
            'old_id' => $this->faker->optional()->randomNumber(6),
            'exam_id' => $this->faker->randomElement($validExamIds),
            'billing_type' => $this->faker->randomElement($validBillingIds),
            'animal_id' => Animal::factory(),
            'owner_id' => Owner::factory(),
            'sample_type_id' => $this->faker->randomElement($validSampleTypeIds),
            'responsible_collect' => $this->faker->optional()->name(),
            'responsible_collect_id' => null,
            'user_registered' => null,
            'user_updated' => null,
            'user_released' => null,
            'user_representative' => null,
            'is_technique' => 0,
            'is_default' => 0,
            'is_released' => 0,
            'priority' => 0,
            'show_client' => 1,
            'send_again' => 0,
            'external_registry' => $this->faker->optional()->numerify('EXT####'),
            'file_name' => null,
            'value' => $this->faker->randomFloat(2, 50, 500),
            'representative_percentage' => null,
            'is_marked' => false,
            'uploaded_at' => null,
            'released_at' => null,
            'collected_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'added_spreadsheet_at' => null,
            'external_sample_date' => null,
            'canceled_at' => null,
            'is_sent' => 0,
        ];
    }

    /**
     * Indicate that the sample is released.
     */
    public function released()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_released' => 1,
                'released_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
                'user_released' => 1, // Assuming user ID 1 exists
            ];
        });
    }
}