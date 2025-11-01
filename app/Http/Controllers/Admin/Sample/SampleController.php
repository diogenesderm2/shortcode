<?php

namespace App\Http\Controllers\Admin\Sample;

use App\Http\Controllers\Controller;
use App\Models\Admin\Sample\Sample;
use App\Models\Admin\Owner\Owner;
use App\Models\Admin\Animal\Animal;
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
        
        return Inertia::render("Admin/Sample/Create", [
            'owners' => $owners,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SampleRequest $request)
    {
        $sample = Sample::create($request->validated());

        return redirect()->route('admin.samples.create')->with('message', 'Amostra cadastrada com sucesso');
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
        $type = $request->input('type'); // child | father | mother
        $desiredGenre = null;

        if ($type === 'father') {
            $desiredGenre = 1; // Macho
        } elseif ($type === 'mother') {
            $desiredGenre = 2; // Fêmea
        }

        $animalByRg = Animal::where('register', $request->rg)
            ->with(['animalType', 'breed'])
            ->first();

        $sexMismatch = false;
        if ($animalByRg && $desiredGenre !== null) {
            $sexMismatch = $animalByRg->genre !== $desiredGenre;
        }

        return response()->json([
            'exists' => !!$animalByRg,
            'animal' => $animalByRg && !$sexMismatch ? $animalByRg : null,
            'sex_mismatch' => $sexMismatch,
        ]);
    }

    /**
     * Get all animal types
     */
    public function getAnimalTypes()
    {
        $animalTypes = \App\Models\Admin\Animal\AnimalType::select('id', 'name', 'description')->get();
        return response()->json($animalTypes);
    }

    /**
     * Get breeds by animal type
     */
    public function getBreedsByType($animalTypeId)
    {
        $breeds = \App\Models\Admin\Animal\Breed::where('animal_type_id', $animalTypeId)
            ->select('id', 'name', 'description')
            ->get();
        return response()->json($breeds);
    }
}