<?php

namespace Database\Seeders\Admin\Owner;

use App\Models\Admin\Owner\Owner;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OwnerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


       User::factory()
       ->count(5)
       ->has(Owner::factory()->count(3))
       ->create();
    }
}
