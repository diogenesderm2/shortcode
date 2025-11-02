<?php

namespace Database\Seeders;

use App\Models\Admin\Sample\Sample;
use Illuminate\Database\Seeder;

class SampleSeeder extends Seeder
{
    public function run(): void
    {
        // Criar 7 amostras normais (não liberadas)
        Sample::factory(7)->create();
        
        // Criar 3 amostras liberadas para testar a funcionalidade de data de liberação
        Sample::factory(3)->released()->create();
        
        $this->command->info('Criadas 10 amostras: 7 pendentes e 3 liberadas com data de liberação');
    }
}
