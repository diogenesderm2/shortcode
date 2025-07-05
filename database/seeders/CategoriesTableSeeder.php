<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin\Categories\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            Category::create([
                'name' => "Categoria de Exemplo $i",
                'slug' => "categoria-exemplo-$i",
                'description' => "Descrição da categoria de exemplo número $i.",
                'image' => "imagem-da-categoria-$i.jpg",
                'is_active' => true,
            ]);
        }
    }
}
