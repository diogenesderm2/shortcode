<?php

namespace Database\Seeders;

use App\Models\Admin\Sample\Sample;
use Illuminate\Database\Seeder;

class SampleSeeder extends Seeder
{
    public function run(): void
    {
        Sample::factory(10)->create();
    }
}
