<?php

namespace App\Http\Controllers\Admin\Cagetories;

use App\Http\Controllers\Controller;
use App\Models\Admin\Categories\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('posts')->paginate(100);

       return  Inertia::render("Admin/Categories/Index", [
            'categories' => $categories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string|max:1000',
            'slug' => 'nullable|string|max:255|unique:categories',
            'is_active' => 'boolean'
        ]);

        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'slug' => $request->slug ?: Str::slug($request->name),
            'is_active' => $request->boolean('is_active', true)
        ]);

        return redirect()->back()->with('message', 'Categoria criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string|max:1000',
            'slug' => 'nullable|string|max:255|unique:categories,slug,' . $id,
            'is_active' => 'boolean'
        ]);

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'slug' => $request->slug ?: Str::slug($request->name),
            'is_active' => $request->boolean('is_active', true)
        ]);

        return redirect()->back()->with('message', 'Categoria atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
