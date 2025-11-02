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
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
            OwnerTableSeeder::class,
            SampleTypeSeeder::class,
            ExamTypeSeeder::class,
            BillingTypeSeeder::class,
            TestTypeSeeder::class,
            AnimalSeeder::class,
            SampleSeeder::class,
            GeneticDataSeeder::class,
            TestSeeder::class, // Adicionar seeder de testes
        ]);
    }
}
