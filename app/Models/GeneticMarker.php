<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneticMarker extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'chromosome',
        'position',
        'ref_allele',
        'alt_allele',
        'description'
    ];

    /**
     * Relacionamento com resultados genéticos
     */
    public function geneticResults()
    {
        return $this->hasMany(GeneticResult::class, 'marker_id');
    }
}