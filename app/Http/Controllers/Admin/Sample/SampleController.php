<?php

namespace App\Http\Controllers\Admin\Sample;

use App\Http\Controllers\Controller;
use App\Models\Admin\Sample\Sample;
use App\Models\Admin\Owner\Owner;
use App\Models\Admin\Animal\Animal;
use App\Models\Admin\ExamType;
use App\Models\Admin\SampleType;
use App\Models\Admin\BillingType;
use App\Models\Admin\TestType;
use App\Models\Admin\Test;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Admin\Sample\SampleRequest;
use App\Http\Requests\Admin\Animal\AnimalRequest;

class SampleController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $owners = Owner::select('id', 'name', 'property')->get();
        $examTypes = ExamType::select('id', 'name', 'animal_type_id')->get();
        $sampleTypes = SampleType::select('id', 'name')->get();
        $billingTypes = BillingType::select('id', 'type', 'animal_type_id')->get();
        $testTypes = TestType::select('id', 'name', 'initials')->get();
        
        return Inertia::render("Admin/Sample/Create", [
            'owners' => $owners,
            'examTypes' => $examTypes,
            'sampleTypes' => $sampleTypes,
            'billingTypes' => $billingTypes,
            'testTypes' => $testTypes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SampleRequest $request)
    {
        try {
            // Criar a amostra
            $sampleData = $request->validated();
            
            // Separar campos que pertencem ao Test
            $testData = [
                'test_type_id' => $sampleData['test_type_id'] ?? null,
                'father_id' => $sampleData['father_id'] ?? null,
                'mother_id' => $sampleData['mother_id'] ?? null,
                'child_id' => $sampleData['child_id'] ?? null,
                'comments' => $sampleData['comments'] ?? null,
            ];
            
            // Remover campos do test dos dados da amostra
            unset($sampleData['test_type_id'], $sampleData['father_id'], $sampleData['mother_id'], 
                  $sampleData['child_id'], $sampleData['comments']);
            
            // Mapear is_technique para a tabela samples (já existe na tabela)
            if (isset($sampleData['is_technique'])) {
                $sampleData['is_technique'] = $sampleData['is_technique'] ? 1 : 0;
            }
            
            // Definir a nova amostra como padrão
            $sampleData['is_default'] = 1;
            
            // Adicionar usuário que registrou
            $sampleData['user_registered'] = auth()->id();
            
            $sample = Sample::create($sampleData);

            // Atualizar todas as outras amostras do mesmo tipo de exame para is_default = 0
            Sample::where('exam_id', $sampleData['exam_id'])
                  ->where('id', '!=', $sample->id)
                  ->update(['is_default' => 0]);

            // Criar o teste associado à amostra
            if ($testData['test_type_id']) {
                Test::create([
                    'sample_id' => $sample->id,
                    'dad_id' => $testData['father_id'],
                    'mom_id' => $testData['mother_id'],
                    'type_id' => $testData['test_type_id'],
                    'user_registered' => auth()->id(),
                    'responsible_collect' => $sampleData['responsible_collect'] ?? null,
                    'is_technique' => isset($sampleData['is_technique']) ? (bool)$sampleData['is_technique'] : false,
                    'is_default' => true, // O teste também será marcado como padrão
                    'is_retest' => false,
                    'comments' => $testData['comments'],
                    'is_read' => false,
                ]);
            }

            return redirect()->route('admin.samples.create')->with('message', 'Amostra e teste cadastrados com sucesso');
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Erro ao cadastrar amostra: ' . $e->getMessage()]);
        }
    }

    /**
     * Get animals by owner
     */
    public function getAnimalsByOwner(Request $request)
    {
        $animals = Animal::where('owner_id', $request->owner_id)
            ->select('id', 'name', 'rg', 'sex')
            ->get();

        return response()->json($animals);
    }

    /**
     * Store a newly created animal.
     */
    public function storeAnimal(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:120',
            'register' => 'required|string|max:550|unique:animals,register',
            'protocol' => 'nullable|string|max:20',
            'birth' => 'nullable|date',
            'genre' => 'required|integer|in:0,1,2',
            'animal_type' => 'required|exists:animal_types,id',
            'breed_id' => 'required|exists:breeds,id',
        ]);

        $animal = Animal::create($request->all());
        $animal->load(['animalType', 'breed']);

        return response()->json([
            'animal' => $animal,
            'message' => 'Animal cadastrado com sucesso'
        ]);
    }

    /**
     * Show the form for adding samples to form.
     */
    public function addToForm()
    {
        return Inertia::render("Admin/Sample/AddToForm");
    }

    /**
     * Search sample by ID.
     */
    public function searchByCode(Request $request)
    {
        $request->validate([
            'sample_code' => 'required|string'
        ]);

        // Buscar pelo ID da amostra
        $sample = Sample::with(['owner', 'animal'])
            ->where('id', $request->sample_code)
            ->first();

        if ($sample) {
            return response()->json([
                'success' => true,
                'sample' => $sample
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Amostra não encontrada'
        ], 404);
    }

    /**
     * Generate form with selected samples
     */
    public function generateForm(Request $request)
    {
        $request->validate([
            'samples' => 'required|array|min:1',
            'samples.*.id' => 'required|exists:samples,id',
            'form_number' => 'required|string'
        ]);

        $sampleIds = collect($request->samples)->pluck('id');
        
        $samples = Sample::with(['owner', 'animal'])
            ->whereIn('id', $sampleIds)
            ->get();

        return Inertia::render('Admin/Sample/FormView', [
            'samples' => $samples,
            'formNumber' => $request->form_number,
            'generatedAt' => now()->format('d/m/Y H:i:s')
        ]);
    }

    /**
     * Check if animal exists by RG
     */
    public function checkAnimalByRg(Request $request)
    {
        $request->validate([
            'rg' => 'required|string',
            'owner_id' => 'required|integer',
            'type' => 'required|string|in:child,father,mother'
        ]);

        $type = $request->input('type'); // child | father | mother
        $desiredGenre = null;

        if ($type === 'father') {
            $desiredGenre = 1; // Macho
        } elseif ($type === 'mother') {
            $desiredGenre = 2; // Fêmea
        }

        // Buscar todos os animais com o mesmo RG e owner_id
        $animalsByRg = Animal::where('register', $request->rg)
            ->where('owner_id', $request->owner_id)
            ->with(['animalType', 'breed', 'owner'])
            ->get();

        if ($animalsByRg->isEmpty()) {
            return response()->json([
                'exists' => false,
                'animal' => null,
                'animals' => [],
                'multiple' => false,
                'sex_mismatch' => false,
            ]);
        }

        // Se há múltiplos animais com o mesmo RG
        if ($animalsByRg->count() > 1) {
            // Filtrar por sexo se necessário
            $filteredAnimals = $animalsByRg;
            if ($desiredGenre !== null) {
                $filteredAnimals = $animalsByRg->where('genre', $desiredGenre);
            }

            return response()->json([
                'exists' => true,
                'animal' => null,
                'animals' => $filteredAnimals->values(),
                'multiple' => true,
                'sex_mismatch' => false,
            ]);
        }

        // Apenas um animal encontrado
        $animalByRg = $animalsByRg->first();
        $sexMismatch = false;
        if ($animalByRg && $desiredGenre !== null) {
            $sexMismatch = $animalByRg->genre !== $desiredGenre;
        }

        return response()->json([
            'exists' => true,
            'animal' => $animalByRg && !$sexMismatch ? $animalByRg : null,
            'animals' => [],
            'multiple' => false,
            'sex_mismatch' => $sexMismatch,
        ]);
    }

    /**
     * Search animals by name (autocomplete)
     */
    public function searchAnimalsByName(Request $request)
    {
        $query = $request->input('query');
        $type = $request->input('type', 'child'); // child | father | mother
        $ownerId = $request->input('owner_id');
        
        \Log::info("Animal search request", [
            'query' => $query,
            'type' => $type,
            'owner_id' => $ownerId
        ]);
        
      

        $desiredGenre = null;
        if ($type === 'father') {
            $desiredGenre = 1; // Macho
        } elseif ($type === 'mother') {
            $desiredGenre = 2; // Fêmea
        }

        // Buscar animais por nome
        $animalsQuery = Animal::where('name', 'like', "%{$query}%")
            ->with(['animalType', 'breed', 'owner']);

        // Filtrar por proprietário se fornecido
        if ($ownerId) {
            $animalsQuery->where('owner_id', $ownerId);
        }

        // Debug: Contar animais antes do filtro de sexo
        $totalBeforeGenderFilter = $animalsQuery->count();
        \Log::info("Animals found before gender filter", ['count' => $totalBeforeGenderFilter]);

        // Filtrar por sexo se necessário
        if ($desiredGenre !== null) {
            $animalsQuery->where('genre', $desiredGenre);
        }

        $animals = $animalsQuery->get();
        
        \Log::info("Final search results", [
            'type' => $type,
            'desired_genre' => $desiredGenre,
            'total_found' => $animals->count(),
            'animals' => $animals->pluck('name', 'id')->toArray()
        ]);

        // Se não encontrou animais com filtro de sexo, buscar sem filtro para debug
        if ($animals->isEmpty() && $desiredGenre !== null) {
            $allAnimals = Animal::where('name', 'like', "%{$query}%")
                ->with(['animalType', 'breed', 'owner'])
                ->when($ownerId, function($q) use ($ownerId) {
                    return $q->where('owner_id', $ownerId);
                })
                ->limit(10)
                ->get();
                
            \Log::info("Debug - All animals without gender filter", [
                'count' => $allAnimals->count(),
                'animals' => $allAnimals->map(function($animal) {
                    return [
                        'id' => $animal->id,
                        'name' => $animal->name,
                        'genre' => $animal->genre,
                        'genre_text' => $animal->genre_text
                    ];
                })->toArray()
            ]);
        }

        return response()->json([
            'animals' => $animals,
            'multiple' => $animals->count() > 1,
        ]);
    }

    /**
     * Get all animal types
     */
    public function getAnimalTypes()
    {
        $animalTypes = \App\Models\Admin\Animal\AnimalType::select('id', 'name')->get();
        return response()->json($animalTypes);
    }

    /**
     * Debug method to check animal data
     */
    public function debugAnimals(Request $request)
    {
        $ownerId = $request->input('owner_id');
        
        $query = Animal::with(['animalType', 'breed', 'owner']);
        
        if ($ownerId) {
            $query->where('owner_id', $ownerId);
        }
        
        $animals = $query->limit(20)->get();
        
        $debug = [
            'total_animals' => Animal::count(),
            'total_males' => Animal::where('genre', 1)->count(),
            'total_females' => Animal::where('genre', 2)->count(),
            'total_undefined' => Animal::where('genre', 0)->count(),
            'animals_sample' => $animals->map(function($animal) {
                return [
                    'id' => $animal->id,
                    'name' => $animal->name,
                    'register' => $animal->register,
                    'genre' => $animal->genre,
                    'genre_text' => $animal->genre_text,
                    'owner_id' => $animal->owner_id,
                    'owner_name' => $animal->owner?->name
                ];
            })
        ];
        
        if ($ownerId) {
            $debug['owner_animals_total'] = Animal::where('owner_id', $ownerId)->count();
            $debug['owner_males'] = Animal::where('owner_id', $ownerId)->where('genre', 1)->count();
            $debug['owner_females'] = Animal::where('owner_id', $ownerId)->where('genre', 2)->count();
        }
        
        return response()->json($debug);
    }

    /**
     * Get breeds by animal type
     */
    public function getBreedsByType($animalTypeId)
    {
        $breeds = \App\Models\Admin\Animal\Breed::where('animal_type_id', $animalTypeId)
            ->select('id', 'name')
            ->get();
        return response()->json($breeds);
    }

    /**
     * Release a sample
     */
    public function release(Request $request, Sample $sample)
    {
        // Verificar se a amostra já está liberada
        if ($sample->is_released) {
            return response()->json([
                'success' => false,
                'message' => 'Esta amostra já está liberada.'
            ], 400);
        }

        // Atualizar a amostra
        $sample->update([
            'is_released' => true,
            'released_at' => now(),
            'user_released' => auth()->id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Amostra liberada com sucesso!',
            'sample' => $sample->fresh()
        ]);
    }
}