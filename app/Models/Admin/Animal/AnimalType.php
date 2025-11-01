<?php

namespace App\Models\Admin\Animal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnimalType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
    ];

    /**
     * Get the animals for the animal type.
     */
    public function animals()
    {
        return $this->hasMany(Animal::class, 'animal_type');
    }

    /**
     * Get the breeds for the animal type.
     */
    public function breeds()
    {
        return $this->hasMany(Breed::class);
    }
}