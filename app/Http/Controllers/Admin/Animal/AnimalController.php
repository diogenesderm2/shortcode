<?php

namespace App\Http\Controllers\Admin\Animal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Animal\AnimalRequest;
use App\Models\Admin\Animal\Animal;
use App\Models\Admin\Owner\Owner;
use App\Models\Admin\Sample\Sample;
use App\Models\Admin\TestType;
use App\Services\GeneticQualificationService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AnimalController extends Controller
{
    /**
     * Método de teste temporário para verificar as modificações
     */
    public function testGeneticCalculation()
    {
        // Dados de teste simulados
        $testMarkers = collect([
            [
                'marker_id' => 1,
                'marker_name' => 'Marker 1',
                'animal_data' => [
                    ['allele_1' => 'A', 'allele_2' => 'T', 'result_value' => null],
                    ['allele_1' => 'G', 'allele_2' => 'C', 'result_value' => null]
                ],
                'father_data' => [
                    ['allele_1' => 'A', 'allele_2' => 'G', 'result_value' => null]
                ],
                'mother_data' => [
                    ['allele_1' => 'T', 'allele_2' => 'C', 'result_value' => null]
                ]
            ],
            [
                'marker_id' => 2,
                'marker_name' => 'Marker 2',
                'animal_data' => [
                    ['allele_1' => 'C', 'allele_2' => 'G', 'result_value' => null]
                ],
                'father_data' => [
                    ['allele_1' => 'C', 'allele_2' => 'T', 'result_value' => null]
                ],
                'mother_data' => [
                    ['allele_1' => 'G', 'allele_2' => 'A', 'result_value' => null]
                ]
            ],
            [
                'marker_id' => 3,
                'marker_name' => 'Marker 3',
                'animal_data' => [
                    ['allele_1' => 'T', 'allele_2' => 'A', 'result_value' => null]
                ],
                'father_data' => [
                    ['allele_1' => 'T', 'allele_2' => 'G', 'result_value' => null]
                ],
                'mother_data' => null // Sem dados da mãe para este marcador
            ]
        ]);

        $stats = $this->calculateDetailedQualification($testMarkers);
        
        return response()->json([
            'test_data' => $testMarkers->toArray(),
            'calculated_stats' => $stats,
            'expected_results' => [
                'father_compatible' => 3, // Todos os 3 marcadores são compatíveis com o pai
                'mother_compatible' => 2, // Apenas 2 marcadores têm dados da mãe e são compatíveis
                'father_total_markers' => 3, // Pai tem dados para todos os 3 marcadores
                'mother_total_markers' => 2, // Mãe tem dados para apenas 2 marcadores
                'father_percentage' => 100.0, // 3/3 = 100%
                'mother_percentage' => 100.0  // 2/2 = 100%
            ]
        ]);
    }

    public function index(Request $request)
    {
        $query = Animal::with(['owner', 'breed', 'animalType']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('registration_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('owner', function ($ownerQuery) use ($request) {
                      $ownerQuery->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        if ($request->owner_id) {
            $query->where('owner_id', $request->owner_id);
        }

        $animals = $query->latest()->paginate(10);

        return Inertia::render('Admin/Animal/Index', [
            'animals' => $animals,
            'filters' => $request->only(['search', 'owner_id']),
            'owners' => Owner::select('id', 'name')->get()
        ]);
    }

    /**
     * Store a newly created animal in storage.
     */
    public function store(AnimalRequest $request)
    {
        $animal = Animal::create($request->validated());

        return redirect()->route('admin.animals.index')
            ->with('message', 'Animal cadastrado com sucesso!');
    }

    /**
     * Show the form for editing the specified animal.
     */
    public function edit(Animal $animal)
    {
        $animal->load('owner');
        
        // Get samples related to this animal
        $samples = Sample::where('animal_id', $animal->id)
            ->with(['owner', 'animal'])
            ->get();

        return Inertia::render('Admin/Animal/Edit', [
            'animal' => $animal,
            'samples' => $samples,
            'owners' => Owner::select('id', 'name')->get()
        ]);
    }

    /**
     * Update the specified animal in storage.
     */
    public function update(AnimalRequest $request, Animal $animal)
    {
        $animal->update($request->validated());

        return redirect()->route('admin.animals.edit', $animal)
            ->with('message', 'Animal atualizado com sucesso!');
    }

    /**
     * Remove the specified animal from storage.
     */
    public function destroy(Animal $animal)
    {
        $animal->delete();

        return redirect()->route('admin.animals.index')
            ->with('message', 'Animal excluído com sucesso!');
    }

    /**
     * Display the specified animal.
     */
    public function show(Animal $animal)
    {
        $animal->load(['owner', 'breed', 'animalType']);

        $samples = Sample::where('animal_id', $animal->id)
            ->with(['owner', 'animal', 'examType', 'billingType', 'tests.testType', 'tests.qualification'])
            ->orderByDesc('uploaded_at')
            ->get();

        return Inertia::render('Admin/Animal/Show', [
            'animal' => $animal,
            'samples' => $samples
        ]);
    }

    /**
     * Get genetic results for an animal
     */
    public function getGeneticResults(Animal $animal)
    {
        // Buscar todas as amostras do animal
        $sampleIds = Sample::where('animal_id', $animal->id)->pluck('id');

        if ($sampleIds->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Nenhuma amostra encontrada para este animal.',
                'data' => []
            ]);
        }

        // Buscar resultados genéticos das amostras
        $geneticResults = \App\Models\GeneticResult::whereIn('sample_id', $sampleIds)
            ->with(['marker', 'sample'])
            ->orderBy('marker_id')
            ->get();

        if ($geneticResults->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Nenhum resultado genético encontrado para este animal.',
                'data' => []
            ]);
        }

        // Transform results to match modal expectations
        $transformedResults = $geneticResults->map(function ($result) {
            return [
                'id' => $result->id,
                'allele1' => $result->allele_1,
                'allele2' => $result->allele_2,
                'genotype' => $result->genotype,
                'marker' => [
                    'id' => $result->marker->id,
                    'name' => $result->marker->name,
                    'chromosome' => $result->marker->chromosome,
                    'position' => $result->marker->position,
                    'ref_allele' => $result->marker->ref_allele,
                    'alt_allele' => $result->marker->alt_allele,
                ],
                'sample' => [
                    'id' => $result->sample->id,
                    'code' => $result->sample->id,
                ]
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Resultados genéticos encontrados com sucesso!',
            'results' => $transformedResults,
            'total' => $transformedResults->count(),
            'animal' => [
                'id' => $animal->id,
                'name' => $animal->name,
                'register' => $animal->register,
            ]
        ]);
    }

    /**
     * Busca resultados genéticos para um animal específico
     */
    private function getGeneticResultsForAnimal(Animal $animal)
    {
        return \App\Models\GeneticResult::whereHas('sample', function ($query) use ($animal) {
                $query->where('animal_id', $animal->id);
            })
            ->with(['marker', 'sample'])
            ->get()
            ->groupBy('marker_id')
            ->map(function ($results) {
                return $results->map(function ($result) {
                    return [
                        'marker_name' => $result->marker->name ?? 'Marcador desconhecido',
                        'allele_1' => $result->allele_1,
                        'allele_2' => $result->allele_2,
                        'result_value' => $result->genotype ?? ($result->allele_1 . '/' . $result->allele_2),
                        'confidence' => $result->quality_metrics['confidence'] ?? null,
                        'sample_id' => $result->sample_id
                    ];
                });
            });
    }

    /**
     * Get tests for an animal
     */
    public function getTests(Animal $animal)
    {
        // Log para debug
        \Log::info('getTests chamado para animal ID: ' . $animal->id);
        
        // Buscar todas as amostras do animal
        $sampleIds = Sample::where('animal_id', $animal->id)->pluck('id');
        
        \Log::info('Sample IDs encontrados: ' . $sampleIds->toJson());

        if ($sampleIds->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Nenhuma amostra encontrada para este animal.',
                'tests' => []
            ]);
        }

        // Buscar testes das amostras
        $tests = \App\Models\Admin\Test::whereIn('sample_id', $sampleIds)
            ->with(['sample', 'testType', 'father', 'mother', 'qualification'])
            ->orderBy('created_at', 'desc')
            ->get();

        \Log::info('Testes encontrados: ' . $tests->count());

        if ($tests->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Nenhum teste encontrado para este animal.',
                'tests' => []
            ]);
        }

        // Transform tests to match modal expectations
        $transformedTests = $tests->map(function ($test) {
            return [
                'id' => $test->id,
                'sample_id' => $test->sample_id,
                'test_type' => $test->testType ? $test->testType->name : 'N/A',
                'father' => $test->father ? $test->father->name : null,
                'mother' => $test->mother ? $test->mother->name : null,
                'is_default' => $test->is_default,
                'comments' => $test->comments,
                'created_at' => $test->created_at->format('d/m/Y H:i'),
                'qualification' => $test->qualification ? [
                    'id' => $test->qualification->id,
                    'status' => $test->qualification->status,
                    'status_label' => $test->qualification->status_label,
                    'status_color' => $test->qualification->status_color,
                    'confidence_score' => $test->qualification->confidence_score,
                    'type' => $test->qualification->type,
                    'type_label' => $test->qualification->type_label,
                    'notes' => $test->qualification->notes,
                    'calculated_at' => $test->qualification->calculated_at?->format('d/m/Y H:i'),
                ] : null,
                'sample' => [
                    'id' => $test->sample->id,
                    'code' => $test->sample->id,
                ]
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Testes encontrados com sucesso!',
            'tests' => $transformedTests,
            'total' => $transformedTests->count(),
            'animal' => [
                'id' => $animal->id,
                'name' => $animal->name,
                'register' => $animal->register,
            ]
        ]);
    }

    /**
     * Calcula qualificações genéticas para os testes de um animal
     */
    public function calculateQualifications(Animal $animal)
    {
        try {
            $qualificationService = new GeneticQualificationService();
            
            // Buscar todas as amostras do animal
            $samples = $animal->samples()->with(['tests.testType', 'tests.father', 'tests.mother'])->get();
            
            if ($samples->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhuma amostra encontrada para este animal.',
                    'results' => []
                ]);
            }

            $results = [];
            $totalTests = 0;
            $successfulCalculations = 0;
            $errors = [];

            // Iterar por todas as amostras e seus testes
            foreach ($samples as $sample) {
                foreach ($sample->tests as $test) {
                    $totalTests++;
                    
                    try {
                        $result = $qualificationService->calculateQualification($test);
                        
                        if ($result['success']) {
                            $successfulCalculations++;
                            $results[] = [
                                'test_id' => $test->id,
                                'sample_id' => $sample->id,
                                'status' => 'success',
                                'qualification' => $result['qualification']->toArray(),
                                'message' => $result['message']
                            ];
                        } else {
                            $errors[] = [
                                'test_id' => $test->id,
                                'sample_id' => $sample->id,
                                'error' => $result['error'] ?? 'Erro desconhecido'
                            ];
                        }
                    } catch (\Exception $e) {
                        $errors[] = [
                            'test_id' => $test->id,
                            'sample_id' => $sample->id,
                            'error' => $e->getMessage()
                        ];
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Processamento concluído: {$successfulCalculations}/{$totalTests} qualificações calculadas com sucesso.",
                'results' => $results,
                'summary' => [
                    'total_tests' => $totalTests,
                    'successful_calculations' => $successfulCalculations,
                    'errors_count' => count($errors),
                    'errors' => $errors
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro ao calcular qualificações genéticas', [
                'animal_id' => $animal->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro interno ao calcular qualificações: ' . $e->getMessage(),
                'results' => []
            ], 500);
        }
    }

    /**
     * Busca dados genéticos comparativos do animal, pai e mãe
     */
    public function getGeneticComparison(Animal $animal)
    {
        try {
            // Buscar dados genéticos do animal
            $animalResults = $this->getGeneticResultsForAnimal($animal);
            
            // Buscar dados dos pais através da tabela tests
            $fatherResults = [];
            $motherResults = [];
            $father = null;
            $mother = null;
            
            // Buscar testes relacionados ao animal para obter dad_id e mom_id
            $tests = \App\Models\Admin\Test::whereHas('sample', function($query) use ($animal) {
                $query->where('animal_id', $animal->id);
            })->with(['father', 'mother'])->get();
            
            if ($tests->isNotEmpty()) {
                $test = $tests->first(); // Pegar o primeiro teste encontrado
                
                if ($test->dad_id) {
                    $father = $test->father;
                    $fatherResults = $this->getGeneticResultsForAnimal($test->father);
                }
                
                if ($test->mom_id) {
                    $mother = $test->mother;
                    $motherResults = $this->getGeneticResultsForAnimal($test->mother);
                }
            }

            // Organizar dados por marcador genético
            $markers = $this->organizeMarkerData($animalResults, $fatherResults, $motherResults);

            // Calcular estatísticas detalhadas de qualificação
            $qualificationStats = $this->calculateDetailedQualification($markers);

            return response()->json([
                'success' => true,
                'data' => [
                    'animal' => [
                        'id' => $animal->id,
                        'name' => $animal->name,
                        'register' => $animal->register
                    ],
                    'father' => $father,
                    'mother' => $mother,
                    'father_qualification' => $qualificationStats['father_percentage'] ?? 0,
                    'mother_qualification' => $qualificationStats['mother_percentage'] ?? 0,
                    'qualification_stats' => $qualificationStats,
                    'markers' => $markers
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro ao buscar dados genéticos comparativos', [
                'animal_id' => $animal->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar dados genéticos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Organiza dados dos marcadores para comparação
     */
    private function organizeMarkerData($animalResults, $fatherResults, $motherResults)
    {
        $allMarkers = collect();
        
        // Coletar todos os marcadores únicos
        $animalMarkers = collect($animalResults)->keys();
        $fatherMarkers = collect($fatherResults)->keys();
        $motherMarkers = collect($motherResults)->keys();
        
        $allMarkerIds = $animalMarkers->merge($fatherMarkers)->merge($motherMarkers)->unique();
        
        return $allMarkerIds->map(function ($markerId) use ($animalResults, $fatherResults, $motherResults) {
            $marker = \App\Models\GeneticMarker::find($markerId);
            
            // Converter Collections em arrays para compatibilidade
            $animalData = isset($animalResults[$markerId]) ? $animalResults[$markerId]->toArray() : null;
            $fatherData = isset($fatherResults[$markerId]) ? $fatherResults[$markerId]->toArray() : null;
            $motherData = isset($motherResults[$markerId]) ? $motherResults[$markerId]->toArray() : null;
            
            return [
                'marker_id' => $markerId,
                'marker_name' => $marker->name ?? 'Marcador ' . $markerId,
                'animal_data' => $animalData,
                'father_data' => $fatherData,
                'mother_data' => $motherData
            ];
        })->values();
    }

    /**
     * Calcula a porcentagem de qualificação de um pai/mãe
     */
    private function calculateParentQualification($markers, $parentType)
    {
        if (empty($markers)) {
            return 0;
        }

        $totalMarkers = 0;
        $compatibleMarkers = 0;

        foreach ($markers as $marker) {
            // Verificar se temos dados para animal e para o pai/mãe específico
            $animalData = $marker['animal_data'] ?? null;
            $parentData = $marker[$parentType . '_data'] ?? null;

            if (!$animalData || !$parentData || 
                !is_array($animalData) || !is_array($parentData) ||
                empty($animalData) || empty($parentData)) {
                continue;
            }

            $totalMarkers++;

            // Extrair alelos do animal
            $animalAlleles = $this->extractAlleles($animalData);
            
            // Extrair alelos do pai/mãe
            $parentAlleles = $this->extractAlleles($parentData);

            // Verificar se há compatibilidade (pelo menos um alelo em comum)
            $hasCompatibility = !empty(array_intersect($animalAlleles, $parentAlleles));

            if ($hasCompatibility) {
                $compatibleMarkers++;
            }
        }

        if ($totalMarkers === 0) {
            return 0;
        }

        return round(($compatibleMarkers / $totalMarkers) * 100, 1);
    }

    /**
     * Calcula estatísticas detalhadas de qualificação
     */
    private function calculateDetailedQualification($markers)
    {
        $stats = [
            'total_markers' => 0,
            'father_compatible' => 0,
            'mother_compatible' => 0,
            'both_compatible' => 0,
            'father_percentage' => 0,
            'mother_percentage' => 0,
            'father_total_markers' => 0,
            'mother_total_markers' => 0
        ];

        foreach ($markers as $index => $marker) {
            $animalData = $marker['animal_data'] ?? null;
            $fatherData = $marker['father_data'] ?? null;
            $motherData = $marker['mother_data'] ?? null;

            // Verificar se temos dados do animal
            if (!$animalData || !is_array($animalData) || empty($animalData)) {
                continue;
            }

            $animalAlleles = $this->extractAlleles($animalData);
            
            // Verificar compatibilidade com o pai
            $fatherCompatible = false;
            if ($fatherData && is_array($fatherData) && !empty($fatherData)) {
                $stats['father_total_markers']++;
                $fatherAlleles = $this->extractAlleles($fatherData);
                $fatherCompatible = !empty(array_intersect($animalAlleles, $fatherAlleles));
                
                if ($fatherCompatible) {
                    $stats['father_compatible']++;
                }
            }

            // Verificar compatibilidade com a mãe
            $motherCompatible = false;
            if ($motherData && is_array($motherData) && !empty($motherData)) {
                $stats['mother_total_markers']++;
                $motherAlleles = $this->extractAlleles($motherData);
                $motherCompatible = !empty(array_intersect($animalAlleles, $motherAlleles));
                
                if ($motherCompatible) {
                    $stats['mother_compatible']++;
                }
            }

            // Contar se ambos são compatíveis
            if ($fatherCompatible && $motherCompatible) {
                $stats['both_compatible']++;
            }

            // Contar apenas se temos pelo menos um dos pais
            if (($fatherData && is_array($fatherData) && !empty($fatherData)) || 
                ($motherData && is_array($motherData) && !empty($motherData))) {
                $stats['total_markers']++;
            }
        }

        // Calcular porcentagens específicas para cada pai
        if ($stats['father_total_markers'] > 0) {
            $stats['father_percentage'] = round(($stats['father_compatible'] / $stats['father_total_markers']) * 100, 1);
        }
        
        if ($stats['mother_total_markers'] > 0) {
            $stats['mother_percentage'] = round(($stats['mother_compatible'] / $stats['mother_total_markers']) * 100, 1);
        }

        return $stats;
    }

    /**
     * Extrai todos os alelos de um conjunto de dados genéticos
     */
    private function extractAlleles($resultData)
    {
        if (!is_array($resultData)) {
            return [];
        }

        $alleles = [];
        
        foreach ($resultData as $result) {
            if (isset($result['allele_1']) && $result['allele_1'] !== null) {
                $alleles[] = $result['allele_1'];
            }
            if (isset($result['allele_2']) && $result['allele_2'] !== null) {
                $alleles[] = $result['allele_2'];
            }
            if (isset($result['result_value']) && $result['result_value'] !== null && 
                !isset($result['allele_1'])) {
                $alleles[] = $result['result_value'];
            }
        }

        return array_unique(array_filter($alleles));
    }
}