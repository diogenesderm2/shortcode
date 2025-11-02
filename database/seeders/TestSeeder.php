<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin\Test;
use App\Models\Admin\Sample\Sample;
use App\Models\Admin\Animal\Animal;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Criando testes com dados de pai e mãe...');

        // Buscar algumas amostras existentes
        $samples = Sample::with('animal')->limit(5)->get();
        
        if ($samples->isEmpty()) {
            $this->command->warn('Nenhuma amostra encontrada. Execute o SampleSeeder primeiro.');
            return;
        }

        // Buscar animais que podem ser pais (machos)
        $fathers = Animal::where('genre', 1)->limit(10)->get();
        
        // Buscar animais que podem ser mães (fêmeas)
        $mothers = Animal::where('genre', 2)->limit(10)->get();

        if ($fathers->isEmpty() || $mothers->isEmpty()) {
            $this->command->warn('Não há animais suficientes para criar testes de parentesco.');
            return;
        }

        foreach ($samples as $sample) {
            // Criar teste tipo "Pai e Mãe" (type_id = 2)
            Test::create([
                'sample_id' => $sample->id,
                'dad_id' => $fathers->random()->id,
                'mom_id' => $mothers->random()->id,
                'type_id' => 2, // Pai e Mãe
                'user_registered' => 1,
                'is_technique' => false,
                'is_default' => true,
                'is_retest' => false,
                'comments' => 'Teste de parentesco criado pelo seeder',
                'is_read' => false,
            ]);

            $this->command->info("Teste criado para amostra {$sample->id} - Animal: {$sample->animal->name}");
        }

        $this->command->info('Testes com dados de pai e mãe criados com sucesso!');
    }
}