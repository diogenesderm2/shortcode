<?php

namespace App\Http\Controllers\Admin\Animal;

use App\Http\Controllers\Controller;
use App\Models\Admin\Animal\Animal;
use App\Models\Admin\Owner\Owner;
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
            ->with('owner')
            ->select('id', 'name', 'rg', 'birth_date', 'sex', 'owner_id');

        // Apply search filters if provided
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('rg', 'like', "%{$request->search}%");
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
}