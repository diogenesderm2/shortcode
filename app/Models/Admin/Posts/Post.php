<?php

namespace App\Models\Admin\Posts;

use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Categories\Category;
use App\Models\User;
class Post extends Model
{
    protected $fillable = ['title', 'slug', 'content', 'category_id', 'user_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
