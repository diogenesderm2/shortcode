<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Admin\Posts\Post;
use App\Models\Admin\Categories\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class PostTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();

        // Criar um usuário para os testes
        $this->user = User::factory()->create();

        // Criar uma categoria para os testes
        $this->category = Category::factory()->create();
    }

    /** @test */
    public function pode_listar_posts()
    {
        // Criar alguns posts
        Post::factory()->count(5)->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id
        ]);

        $response = $this->actingAs($this->user)
            ->get('/admin/posts');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Admin/Posts/Index')
            ->has('posts.data', 5)
        );
    }

    /** @test */
    public function pode_acessar_pagina_de_criar_post()
    {
        $response = $this->actingAs($this->user)
            ->get('/admin/posts/create');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Admin/Posts/New')
            ->has('categories')
        );
    }

    /** @test */
    public function pode_criar_um_novo_post()
    {
        $postData = [
            'title' => 'Título do Post',
            'content' => 'Conteúdo do post de teste',
            'category_id' => $this->category->id,
        ];

        $response = $this->actingAs($this->user)
            ->post('/admin/posts', $postData);

        $this->assertDatabaseHas('posts', [
            'title' => 'Título do Post',
            'content' => 'Conteúdo do post de teste',
            'category_id' => $this->category->id,
            'user_id' => $this->user->id,
        ]);

        $response->assertRedirect('/admin/posts');
    }

    /** @test */
    public function pode_acessar_pagina_de_editar_post()
    {
        $post = Post::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id
        ]);

        $response = $this->actingAs($this->user)
            ->get("/admin/posts/{$post->id}/edit");

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Admin/Posts/Edit')
            ->has('post')
            ->has('categories')
        );
    }

    /** @test */
    public function pode_atualizar_um_post()
    {
        $post = Post::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id
        ]);

        $updateData = [
            'title' => 'Título Atualizado',
            'content' => 'Conteúdo atualizado do post',
            'category_id' => $this->category->id,
        ];

        $response = $this->actingAs($this->user)
            ->put("/admin/posts/{$post->id}", $updateData);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Título Atualizado',
            'content' => 'Conteúdo atualizado do post',
        ]);

        $response->assertRedirect('/admin/posts');
    }

    /** @test */
    public function pode_excluir_um_post()
    {
        $post = Post::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id
        ]);

        $response = $this->actingAs($this->user)
            ->delete("/admin/posts/{$post->id}");

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
        ]);

        $response->assertRedirect('/admin/posts');
    }

    /** @test */
    public function validacao_requer_titulo_para_criar_post()
    {
        $postData = [
            'content' => 'Conteúdo do post',
            'category_id' => $this->category->id,
        ];

        $response = $this->actingAs($this->user)
            ->post('/admin/posts', $postData);

        $response->assertSessionHasErrors(['title']);
    }

    /** @test */
    public function validacao_requer_conteudo_para_criar_post()
    {
        $postData = [
            'title' => 'Título do Post',
            'category_id' => $this->category->id,
        ];

        $response = $this->actingAs($this->user)
            ->post('/admin/posts', $postData);

        $response->assertSessionHasErrors(['content']);
    }

    /** @test */
    public function validacao_requer_categoria_para_criar_post()
    {
        $postData = [
            'title' => 'Título do Post',
            'content' => 'Conteúdo do post',
        ];

        $response = $this->actingAs($this->user)
            ->post('/admin/posts', $postData);

        $response->assertSessionHasErrors(['category_id']);
    }

    /** @test */
    public function validacao_requer_titulo_para_atualizar_post()
    {
        $post = Post::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id
        ]);

        $updateData = [
            'content' => 'Conteúdo atualizado',
            'category_id' => $this->category->id,
        ];

        $response = $this->actingAs($this->user)
            ->put("/admin/posts/{$post->id}", $updateData);

        $response->assertSessionHasErrors(['title']);
    }

    /** @test */
    public function validacao_requer_conteudo_para_atualizar_post()
    {
        $post = Post::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id
        ]);

        $updateData = [
            'title' => 'Título Atualizado',
            'category_id' => $this->category->id,
        ];

        $response = $this->actingAs($this->user)
            ->put("/admin/posts/{$post->id}", $updateData);

        $response->assertSessionHasErrors(['content']);
    }

    /** @test */
    public function validacao_requer_categoria_para_atualizar_post()
    {
        $post = Post::factory()->create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id
        ]);

        $updateData = [
            'title' => 'Título Atualizado',
            'content' => 'Conteúdo atualizado',
        ];

        $response = $this->actingAs($this->user)
            ->put("/admin/posts/{$post->id}", $updateData);

        $response->assertSessionHasErrors(['category_id']);
    }

    /** @test */
    public function validacao_categoria_deve_existir()
    {
        $postData = [
            'title' => 'Título do Post',
            'content' => 'Conteúdo do post',
            'category_id' => 999, // ID inexistente
        ];

        $response = $this->actingAs($this->user)
            ->post('/admin/posts', $postData);

        $response->assertSessionHasErrors(['category_id']);
    }

    /** @test */
    public function validacao_titulo_nao_pode_ser_muito_longo()
    {
        $postData = [
            'title' => str_repeat('a', 256), // Mais de 255 caracteres
            'content' => 'Conteúdo do post',
            'category_id' => $this->category->id,
        ];

        $response = $this->actingAs($this->user)
            ->post('/admin/posts', $postData);

        $response->assertSessionHasErrors(['title']);
    }
}
