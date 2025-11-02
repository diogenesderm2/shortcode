<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin\Animal\Animal;
use App\Http\Controllers\Admin\Animal\AnimalController;

class TestGeneticComparison extends Command
{
    protected $signature = 'test:genetic-comparison {animal_id?}';
    protected $description = 'Testa a comparação genética para debug';

    public function handle()
    {
        $animalId = $this->argument('animal_id');
        
        if (!$animalId) {
            // Buscar o primeiro animal que tem amostras
            $animal = Animal::whereHas('samples')->first();
            if (!$animal) {
                $this->error('Nenhum animal com amostras encontrado');
                return;
            }
        } else {
            $animal = Animal::find($animalId);
            if (!$animal) {
                $this->error('Animal não encontrado');
                return;
            }
        }

        $this->info("Testando comparação genética para animal: {$animal->name} (ID: {$animal->id})");

        $controller = new AnimalController();
        $response = $controller->getGeneticComparison($animal);
        
        $data = $response->getData(true);
        
        $this->info('Resposta da API:');
        $this->line(json_encode($data, JSON_PRETTY_PRINT));
        
        if (isset($data['data'])) {
            $this->info('Porcentagens:');
            $this->line("Pai: " . ($data['data']['father_qualification'] ?? 'N/A') . "%");
            $this->line("Mãe: " . ($data['data']['mother_qualification'] ?? 'N/A') . "%");
        }
    }
}