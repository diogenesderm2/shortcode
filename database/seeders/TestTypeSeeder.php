<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testTypes = [
            [
                'id' => 1,
                'name' => 'Arquivo Permanente',
                'initials' => 'A. P.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Pai e Mãe',
                'initials' => 'P. M.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Apenas Pai',
                'initials' => 'Ap. Pai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Apenas Mãe',
                'initials' => 'Ap. Mãe',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('test_types')->insert($testTypes);
    }
}