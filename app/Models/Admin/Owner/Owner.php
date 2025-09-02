<?php

namespace App\Models\Admin\Owner;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'user_registered',
        'user_financial',
        'name',
        'rg',
        'cpf',
        'description',
        'cnpj',
        'property',
        'image',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
