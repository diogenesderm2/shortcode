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
        $this->command->info('Criando marcadores genéticos de teste...');
        
        // Criar alguns marcadores genéticos de teste
        $markers = [
            [
                'name' => 'BM1824',
                'chromosome' => '1',
                'position' => 132241,
                'ref_allele' => 'A',
                'alt_allele' => 'G'
            ],
            [
                'name' => 'BM2113',
                'chromosome' => '2',
                'position' => 45678,
                'ref_allele' => 'C',
                'alt_allele' => 'T'
            ],
            [
                'name' => 'TGLA227',
                'chromosome' => '18',
                'position' => 65432,
                'ref_allele' => 'G',
                'alt_allele' => 'A'
            ],
            [
                'name' => 'TGLA126',
                'chromosome' => '20',
                'position' => 98765,
                'ref_allele' => 'T',
                'alt_allele' => 'C'
            ],
            [
                'name' => 'INRA023',
                'chromosome' => '3',
                'position' => 123456,
                'ref_allele' => 'A',
                'alt_allele' => 'T'
            ]
        ];

        foreach ($markers as $markerData) {
            GeneticMarker::firstOrCreate(
                ['name' => $markerData['name']],
                $markerData
            );
        }

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

            // Criar resultados genéticos para cada marcador
            foreach ($createdMarkers as $marker) {
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
                        'genotype' => $allele1 . $allele2
                    ]
                );
            }
            
            $this->command->info("Criados resultados genéticos para animal {$animal->name}");
        }

        $this->command->info('Dados genéticos de teste criados com sucesso!');
    }
}