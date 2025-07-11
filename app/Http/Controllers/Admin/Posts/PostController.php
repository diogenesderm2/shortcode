<?php

namespace App\Http\Controllers\Admin\Posts;

use App\Http\Controllers\Controller;
use App\Models\Admin\Posts\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Admin\Categories\Category;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('category', 'user')->paginate(3);

        return Inertia::render('Admin/Posts/Index', [
            'posts' => $posts
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();

        return Inertia::render('Admin/Posts/New', [
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id'
        ]);

        $post = Post::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'content' => $validated['content'],
            'category_id' => $validated['category_id'],
            'user_id' => $request->user()->id,
        ]);

        // Log para debug
        Log::info('Post criado com sucesso', ['post_id' => $post->id, 'title' => $validated['title']]);

        return redirect()->route('admin.posts.index')
            ->with('message', 'Post criado com sucesso!');
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
        $categories = Category::all();
        $post = Post::findOrFail($id);
        return Inertia::render('Admin/Posts/Edit', [
            'categories' => $categories,
            'post' => $post,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $post = Post::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id'
        ]);

        $post->update($validated);

        // Log para debug
        Log::info('Post atualizado com sucesso', ['post_id' => $id, 'title' => $validated['title']]);

        return redirect()->route('admin.posts.index')
            ->with('message', 'Post atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return redirect()->route('admin.posts.index')
            ->with('message', 'Post excluído com sucesso!');
    }
}
