<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Admin\Posts\Post;
use App\Models\Admin\Categories\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function pode_criar_post_com_factory()
    {
        $post = Post::factory()->create();

        $this->assertInstanceOf(Post::class, $post);
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
        ]);
    }

    /** @test */
    public function post_pertence_a_uma_categoria()
    {
        $category = Category::factory()->create();
        $post = Post::factory()->create([
            'category_id' => $category->id
        ]);

        $this->assertInstanceOf(Category::class, $post->category);
        $this->assertEquals($category->id, $post->category->id);
    }

    /** @test */
    public function post_pertence_a_um_usuario()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id
        ]);

        $this->assertInstanceOf(User::class, $post->user);
        $this->assertEquals($user->id, $post->user->id);
    }

    /** @test */
    public function categoria_pode_ter_muitos_posts()
    {
        $category = Category::factory()->create();
        $posts = Post::factory()->count(3)->create([
            'category_id' => $category->id
        ]);

        $this->assertCount(3, $category->posts);
        $this->assertInstanceOf(Post::class, $category->posts->first());
    }

    /** @test */
    public function usuario_pode_ter_muitos_posts()
    {
        $user = User::factory()->create();
        $posts = Post::factory()->count(3)->create([
            'user_id' => $user->id
        ]);

        $this->assertCount(3, $user->posts);
        $this->assertInstanceOf(Post::class, $user->posts->first());
    }

    /** @test */
    public function pode_criar_post_sem_imagem()
    {
        $post = Post::factory()->withoutImage()->create();

        $this->assertNull($post->image);
    }

    /** @test */
    public function pode_criar_post_inativo()
    {
        $post = Post::factory()->inactive()->create();

        $this->assertFalse($post->is_active);
    }

    /** @test */
    public function post_pode_ser_atualizado()
    {
        $post = Post::factory()->create();
        $newTitle = 'Título Atualizado';

        $post->update(['title' => $newTitle]);

        $this->assertEquals($newTitle, $post->fresh()->title);
    }

    /** @test */
    public function post_pode_ser_excluido()
    {
        $post = Post::factory()->create();
        $postId = $post->id;

        $post->delete();

        $this->assertDatabaseMissing('posts', [
            'id' => $postId
        ]);
    }

    /** @test */
    public function slug_e_gerado_automaticamente()
    {
        $post = Post::factory()->create([
            'title' => 'Meu Post de Teste'
        ]);

        $this->assertEquals('meu-post-de-teste', $post->slug);
    }

    /** @test */
    public function campos_fillable_sao_preenchiveis()
    {
        $post = new Post();
        $fillable = $post->getFillable();

        $expectedFields = ['title', 'slug', 'content', 'category_id', 'user_id'];

        foreach ($expectedFields as $field) {
            $this->assertContains($field, $fillable);
        }
    }

    /** @test */
    public function pode_criar_post_com_dados_especificos()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $postData = [
            'title' => 'Post Específico',
            'slug' => 'post-especifico',
            'content' => 'Conteúdo específico do post',
            'category_id' => $category->id,
            'user_id' => $user->id,
        ];

        $post = Post::factory()->create($postData);

        $this->assertEquals('Post Específico', $post->title);
        $this->assertEquals('post-especifico', $post->slug);
        $this->assertEquals('Conteúdo específico do post', $post->content);
        $this->assertEquals($category->id, $post->category_id);
        $this->assertEquals($user->id, $post->user_id);
    }
}
