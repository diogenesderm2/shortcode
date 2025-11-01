<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExamTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Desabilitar verificação de chaves estrangeiras temporariamente
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Limpar a tabela antes de inserir
        DB::table('exam_types')->truncate();

        // Dados dos tipos de exame
        $examTypes = [
            ['id' => 1, 'animal_type_id' => 1, 'name' => 'Identificação genética'],
            ['id' => 2, 'animal_type_id' => 1, 'name' => 'Beta caseína'],
            ['id' => 5, 'animal_type_id' => 2, 'name' => 'Identificação genética'],
            ['id' => 6, 'animal_type_id' => 2, 'name' => 'HYPP'],
            ['id' => 7, 'animal_type_id' => 3, 'name' => 'Identificação genética'],
            ['id' => 8, 'animal_type_id' => 4, 'name' => 'Identificação genética'],
            ['id' => 15, 'animal_type_id' => 2, 'name' => 'Five Panel'],
        ];

        // Inserir os dados
        foreach ($examTypes as $examType) {
            DB::table('exam_types')->insert([
                'id' => $examType['id'],
                'animal_type_id' => $examType['animal_type_id'],
                'name' => $examType['name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Reabilitar verificação de chaves estrangeiras
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('ExamTypeSeeder executado com sucesso!');
        $this->command->info('Total de tipos de exame inseridos: ' . count($examTypes));
    }
}