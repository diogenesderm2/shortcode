<?php

namespace Database\Seeders;

use Database\Seeders\Admin\Owner\OwnerTableSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            OwnerTableSeeder::class,
            SampleTypeSeeder::class,
            ExamTypeSeeder::class,
            BillingTypeSeeder::class,
            AnimalSeeder::class,
            SampleSeeder::class,
        ]);
    }
}
