<?php

namespace Database\Seeders;

use App\Models\Admin\Animal\Animal;
use Illuminate\Database\Seeder;

class AnimalSeeder extends Seeder
{
    public function run(): void
    {
        Animal::factory(20)->create();
    }
}
