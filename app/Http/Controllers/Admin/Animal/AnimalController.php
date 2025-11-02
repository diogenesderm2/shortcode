<?php

namespace App\Http\Controllers\Admin\Animal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Animal\AnimalRequest;
use App\Models\Admin\Animal\Animal;
use App\Models\Admin\Owner\Owner;
use App\Models\Admin\Sample\Sample;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AnimalController extends Controller
{
    /**
     * Display a listing of the animals.
     */
    public function index(Request $request)
    {
        $query = Animal::query()
            ->with('owner');

        // Apply search filters if provided
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('register', 'like', "%{$request->search}%");
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
            ->with(['owner', 'animal', 'examType', 'billingType'])
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
}