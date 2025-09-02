<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin\Owner\Owner;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         User::factory(100)->create();
         Owner::factory(10)->create();

        $this->call(PostsTableSeeder::class);
    }
}
