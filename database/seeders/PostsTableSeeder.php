<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin\Posts\Post;
use App\Models\User;
use App\Models\Admin\Categories\Category;

class PostsTableSeeder extends Seeder
{
    public function run()
    {
        // Certifique-se de que existam usuários e categorias
        $user = User::first() ?? User::factory()->create();
        $category = Category::first() ?? Category::factory()->create();

        // Cria 10 posts de exemplo
        for ($i = 1; $i <= 10; $i++) {
            Post::create([
                'title' => "Post de Exemplo $i",
                'slug' => "post-exemplo-$i",
                'content' => "Conteúdo do post de exemplo número $i.",
                'category_id' => $category->id,
                'user_id' => $user->id,
            ]);
        }
    }
}
