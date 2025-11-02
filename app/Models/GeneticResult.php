<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Sample\Sample;

class GeneticResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'sample_id',
        'marker_id',
        'allele_1',
        'allele_2',
        'genotype'
    ];

    /**
     * Relacionamento com amostra
     */
    public function sample()
    {
        return $this->belongsTo(Sample::class);
    }

    /**
     * Relacionamento com marcador genético
     */
    public function marker()
    {
        return $this->belongsTo(GeneticMarker::class, 'marker_id');
    }

    /**
     * Mutator para definir genótipo automaticamente
     */
    public function setAllele1Attribute($value)
    {
        $this->attributes['allele_1'] = $value;
        $this->updateGenotype();
    }

    public function setAllele2Attribute($value)
    {
        $this->attributes['allele_2'] = $value;
        $this->updateGenotype();
    }

    /**
     * Atualiza o genótipo baseado nos alelos
     */
    private function updateGenotype()
    {
        if (isset($this->attributes['allele_1']) && isset($this->attributes['allele_2'])) {
            $this->attributes['genotype'] = $this->attributes['allele_1'] . $this->attributes['allele_2'];
        }
    }
}