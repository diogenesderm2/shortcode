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
            OwnerTableSeeder::class,
            AnimalSeeder::class,
            SampleSeeder::class,
        ]);
    }
}
