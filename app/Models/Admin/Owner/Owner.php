<?php

namespace App\Models\Admin\Owner;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Admin\Animal\Animal;
use App\Models\Admin\Sample\Sample;
use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    use HasFactory;
    protected $fillable = [
        'registration',
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

    public function animals()
    {
        return $this->hasMany(Animal::class);
    }

    public function samples()
    {
        return $this->hasMany(Sample::class);
    }
}
