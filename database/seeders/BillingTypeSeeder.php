<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BillingTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Desabilitar verificação de chaves estrangeiras temporariamente
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Limpar a tabela antes de inserir
        DB::table('billing_types')->truncate();

        // Dados dos tipos de cobrança
        $billingTypes = [
            ['id' => 1, 'animal_type_id' => 1, 'type' => 'Identificação genética'],
            ['id' => 2, 'animal_type_id' => 1, 'type' => 'Beta caseína'],
            ['id' => 3, 'animal_type_id' => 1, 'type' => 'Kappa caseína'],
            ['id' => 4, 'animal_type_id' => 1, 'type' => 'Beta Lactoglobulina'],
            ['id' => 5, 'animal_type_id' => 2, 'type' => 'Identificação genética'],
            ['id' => 6, 'animal_type_id' => 2, 'type' => 'HYPP'],
            ['id' => 7, 'animal_type_id' => 1, 'type' => 'Doação'],
            ['id' => 8, 'animal_type_id' => 2, 'type' => 'Doação'],
            ['id' => 9, 'animal_type_id' => 1, 'type' => 'Recoleta'],
            ['id' => 10, 'animal_type_id' => 2, 'type' => 'Recoleta'],
            ['id' => 11, 'animal_type_id' => 3, 'type' => 'Identificação genética'],
            ['id' => 12, 'animal_type_id' => 4, 'type' => 'Identificação genética'],
            ['id' => 13, 'animal_type_id' => 3, 'type' => 'Doação'],
            ['id' => 14, 'animal_type_id' => 4, 'type' => 'Doação'],
            ['id' => 15, 'animal_type_id' => 2, 'type' => 'Five Panel – GBED / HERDA / HY'],
        ];

        // Inserir os dados
        foreach ($billingTypes as $billingType) {
            DB::table('billing_types')->insert([
                'id' => $billingType['id'],
                'animal_type_id' => $billingType['animal_type_id'],
                'type' => $billingType['type'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Reabilitar verificação de chaves estrangeiras
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('BillingTypeSeeder executado com sucesso!');
        $this->command->info('Total de tipos de cobrança inseridos: ' . count($billingTypes));
    }
}