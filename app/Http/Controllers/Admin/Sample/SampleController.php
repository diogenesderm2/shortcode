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
        $owners = Owner::select('id', 'name', 'property_name')->get();
        
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
    public function storeAnimal(AnimalRequest $request)
    {
        $animal = Animal::create($request->validated());

        return response()->json([
            'animal' => $animal,
            'message' => 'Animal cadastrado com sucesso'
        ]);
    }

    /**
     * Check if animal exists by RG
     */
    public function checkAnimalByRg(Request $request)
    {
        $animal = Animal::where('rg', $request->rg)
            ->where('owner_id', $request->owner_id)
            ->first();

        return response()->json([
            'exists' => !!$animal,
            'animal' => $animal
        ]);
    }
}