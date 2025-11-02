<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\GeneticMarker;
use App\Models\GeneticResult;
use App\Models\Admin\Sample\Sample;
use App\Models\Admin\Animal\Animal;
use Database\Seeders\GeneticDataSeeder;

class CheckGeneticData extends Command
{
    protected $signature = 'genetic:check';
    protected $description = 'Verifica e cria dados genéticos de teste se necessário';

    public function handle()
    {
        $this->info('Verificando dados genéticos...');
        
        // Verificar se há marcadores genéticos
        $markersCount = GeneticMarker::count();
        $this->info("Marcadores genéticos encontrados: {$markersCount}");
        
        // Verificar se há resultados genéticos
        $resultsCount = GeneticResult::count();
        $this->info("Resultados genéticos encontrados: {$resultsCount}");
        
        // Verificar se há animais
        $animalsCount = Animal::count();
        $this->info("Animais encontrados: {$animalsCount}");
        
        // Verificar se há samples
        $samplesCount = Sample::count();
        $this->info("Samples encontradas: {$samplesCount}");
        
        if ($markersCount === 0 || $resultsCount === 0) {
            $this->warn('Dados genéticos insuficientes. Executando seeder...');
            
            $seeder = new GeneticDataSeeder();
            $seeder->setCommand($this);
            $seeder->run();
            
            $this->info('Seeder executado com sucesso!');
            
            // Verificar novamente
            $markersCount = GeneticMarker::count();
            $resultsCount = GeneticResult::count();
            $this->info("Novos totais - Marcadores: {$markersCount}, Resultados: {$resultsCount}");
        } else {
            $this->info('Dados genéticos já existem no banco!');
        }
        
        // Testar um animal específico
        $animal = Animal::first();
        if ($animal) {
            $this->info("Testando animal ID: {$animal->id} - {$animal->name}");
            
            $samples = $animal->samples()->count();
            $this->info("Samples do animal: {$samples}");
            
            if ($samples > 0) {
                $sampleIds = $animal->samples()->pluck('id');
                $geneticResults = GeneticResult::whereIn('sample_id', $sampleIds)->count();
                $this->info("Resultados genéticos do animal: {$geneticResults}");
            }
        }
        
        return 0;
    }
}