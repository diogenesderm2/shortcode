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
        $animal->load('owner');
        
        // Get samples related to this animal
        $samples = Sample::where('animal_id', $animal->id)
            ->with(['owner', 'animal'])
            ->get();

        return Inertia::render('Admin/Animal/Show', [
            'animal' => $animal,
            'samples' => $samples
        ]);
    }
}