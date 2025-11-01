<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SampleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Desabilitar verificação de chaves estrangeiras temporariamente
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Limpar a tabela antes de inserir
        DB::table('sample_types')->truncate();

        // Dados dos tipos de amostra
        $sampleTypes = [
            ['id' => 1, 'name' => 'Sangue'],
            ['id' => 2, 'name' => 'Pelo'],
            ['id' => 3, 'name' => 'Saliva'],
            ['id' => 4, 'name' => 'Sêmen'],
            ['id' => 5, 'name' => 'Tecido'],
        ];

        // Inserir os dados
        foreach ($sampleTypes as $sampleType) {
            DB::table('sample_types')->insert([
                'id' => $sampleType['id'],
                'name' => $sampleType['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Reabilitar verificação de chaves estrangeiras
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('SampleTypeSeeder executado com sucesso!');
        $this->command->info('Total de tipos de amostra inseridos: ' . count($sampleTypes));
    }
}