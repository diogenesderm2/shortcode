<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\GeneticMarker;
use App\Models\GeneticResult;
use App\Models\Admin\Sample\Sample;
use App\Models\Admin\Animal\Animal;

class GeneticDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Criando marcadores genéticos a partir do arquivo TSV...');
        
        // Caminho para o arquivo TSV
        $tsvFile = public_path('EXEMPLO_TESTE_PAI_E_MAE.tsv');
        
        if (!file_exists($tsvFile)) {
            $this->command->error("Arquivo TSV não encontrado: {$tsvFile}");
            return;
        }

        // Ler o arquivo TSV
        $handle = fopen($tsvFile, 'r');
        if (!$handle) {
            $this->command->error("Não foi possível abrir o arquivo TSV");
            return;
        }

        $markers = [];
        $lineNumber = 0;
        
        while (($line = fgetcsv($handle, 0, "\t")) !== false) {
            $lineNumber++;
            
            // Pular a primeira linha (cabeçalho)
            if ($lineNumber === 1) {
                continue;
            }
            
            // Verificar se a linha tem pelo menos 5 colunas (TARGET, CHROM, POS, REF, ALT)
            if (count($line) < 5) {
                continue;
            }
            
            $markerName = trim($line[0]); // TARGET
            $chromosome = trim($line[1]); // CHROM
            $position = trim($line[2]);   // POS
            $refAllele = trim($line[3]);  // REF
            $altAllele = trim($line[4]);  // ALT
            
            // Validar dados básicos
            if (empty($markerName) || empty($chromosome) || empty($position)) {
                continue;
            }
            
            // Processar alelos alternativos (podem ser múltiplos separados por vírgula)
            $altAlleles = explode(',', $altAllele);
            $primaryAltAllele = trim($altAlleles[0]);
            
            $markers[] = [
                'name' => $markerName,
                'chromosome' => $chromosome,
                'position' => (int)$position,
                'ref_allele' => $refAllele,
                'alt_allele' => $primaryAltAllele,
                'description' => "Marcador genético do cromossomo {$chromosome} na posição {$position}"
            ];
        }
        
        fclose($handle);
        
        $this->command->info("Processados " . count($markers) . " marcadores do arquivo TSV");
        
        // Criar marcadores no banco de dados
        $createdCount = 0;
        foreach ($markers as $markerData) {
            $marker = GeneticMarker::firstOrCreate(
                ['name' => $markerData['name']],
                $markerData
            );
            
            if ($marker->wasRecentlyCreated) {
                $createdCount++;
            }
        }
        
        $this->command->info("Criados {$createdCount} novos marcadores genéticos no banco de dados");

        $this->command->info('Criando resultados genéticos de teste...');
        
        // Buscar alguns animais e amostras para criar resultados
        $animals = Animal::with('samples')->take(5)->get();
        $createdMarkers = GeneticMarker::all();
        
        foreach ($animals as $animal) {
            // Se o animal não tem amostras, criar uma
            if ($animal->samples->isEmpty()) {
                $sample = Sample::create([
                    'animal_id' => $animal->id,
                    'owner_id' => $animal->owner_id,
                    'exam_id' => 1, // Usar exam_id ao invés de exam_type_id
                    'billing_type' => 1, // Usar billing_type ao invés de billing_type_id
                    'sample_type_id' => 1,
                    'value' => 100.00,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                $this->command->info("Criada amostra {$sample->id} para animal {$animal->name}");
            } else {
                $sample = $animal->samples->first();
            }

            // Criar resultados genéticos para cada marcador (apenas uma amostra dos primeiros 20 marcadores para não sobrecarregar)
            $sampleMarkers = $createdMarkers->take(20);
            foreach ($sampleMarkers as $marker) {
                // Gerar alelos aleatórios baseados nos alelos de referência
                $possibleAlleles = [$marker->ref_allele, $marker->alt_allele];
                $allele1 = $possibleAlleles[array_rand($possibleAlleles)];
                $allele2 = $possibleAlleles[array_rand($possibleAlleles)];
                
                GeneticResult::firstOrCreate(
                    [
                        'sample_id' => $sample->id,
                        'marker_id' => $marker->id
                    ],
                    [
                        'allele_1' => $allele1,
                        'allele_2' => $allele2,
                        'genotype' => $allele1 . '/' . $allele2
                    ]
                );
            }
            
            $this->command->info("Criados resultados genéticos para animal {$animal->name}");
        }

        $this->command->info('Dados genéticos criados com sucesso!');
        $this->command->info("Total de marcadores no banco: " . GeneticMarker::count());
    }
}