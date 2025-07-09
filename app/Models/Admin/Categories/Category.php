<?php

namespace App\Models\Admin\Categories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'image', 'is_active', 'parent_id', 'order'
    ];

    public function posts()
    {
        return $this->hasMany(\App\Models\Admin\Posts\Post::class);
    }
}
