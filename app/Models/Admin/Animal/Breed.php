<?php

namespace App\Models\Admin\Animal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Breed extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'animal_type_id',
    ];

    /**
     * Get the animal type that owns the breed.
     */
    public function animalType()
    {
        return $this->belongsTo(AnimalType::class);
    }

    /**
     * Get the animals for the breed.
     */
    public function animals()
    {
        return $this->hasMany(Animal::class, 'breed_id');
    }
}