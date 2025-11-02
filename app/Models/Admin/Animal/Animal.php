<?php

namespace App\Models\Admin\Animal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Admin\Sample\Sample;
use App\Models\Admin\Owner\Owner;

class Animal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'old_id',
        'animal_type',
        'breed_id',
        'protocol',
        'name',
        'register',
        'genre',
        'birth',
        'owner_id',
    ];

    protected $casts = [
        'birth' => 'date',
    ];

    protected $appends = [
        'rg',
        'birth_date', 
        'sex',
        'genre_text',
        'species'
    ];

    public function animalType()
    {
        return $this->belongsTo(AnimalType::class, 'animal_type');
    }

    public function breed()
    {
        return $this->belongsTo(Breed::class, 'breed_id');
    }

    public function owner()
    {
        return $this->belongsTo(Owner::class, 'owner_id');
    }

    public function samples()
    {
        return $this->hasMany(Sample::class, 'animal_id');
    }

    // Accessor para o sexo em formato legível
    public function getGenreTextAttribute()
    {
        return match($this->genre) {
            1 => 'Macho',
            2 => 'Fêmea',
            default => 'Indefinido'
        };
    }

    // Accessor para compatibilidade com código antigo
    public function getSexAttribute()
    {
        return match($this->genre) {
            1 => 'macho',
            2 => 'femea',
            default => 'indefinido'
        };
    }

    // Accessor para o RG (compatibilidade)
    public function getRgAttribute()
    {
        return $this->register;
    }

    // Accessor para birth_date (compatibilidade)
    public function getBirthDateAttribute()
    {
        if (!$this->birth) {
            return null;
        }
        
        // Se birth já é uma instância de Carbon, formatar
        if ($this->birth instanceof \Carbon\Carbon) {
            return $this->birth->format('Y-m-d');
        }
        
        // Se é uma string, tentar converter
        try {
            return \Carbon\Carbon::parse($this->birth)->format('Y-m-d');
        } catch (\Exception $e) {
            return $this->birth;
        }
    }

    // Accessor para species (compatibilidade)
    public function getSpeciesAttribute()
    {
        return $this->animalType?->name ? strtolower($this->animalType->name) : 'bovino';
    }
}