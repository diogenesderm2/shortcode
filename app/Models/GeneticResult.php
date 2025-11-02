<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Sample\Sample;
use App\Models\User;

class GeneticResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'sample_id',
        'marker_id',
        'allele_1',
        'allele_2',
        'genotype',
        'status',
        'reviewed_by',
        'reviewed_at',
        'review_notes',
        'quality_metrics'
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'quality_metrics' => 'array'
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_UNDER_REVIEW = 'under_review';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

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
     * Relacionamento com usuário que revisou
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Scopes para filtrar por status
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeUnderReview($query)
    {
        return $query->where('status', self::STATUS_UNDER_REVIEW);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    /**
     * Verificar se pode ser revisado
     */
    public function canBeReviewed()
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_UNDER_REVIEW]);
    }

    /**
     * Verificar se está aprovado
     */
    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Verificar se está rejeitado
     */
    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
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

    /**
     * Obter label do status
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Pendente',
            self::STATUS_UNDER_REVIEW => 'Em Revisão',
            self::STATUS_APPROVED => 'Aprovado',
            self::STATUS_REJECTED => 'Rejeitado',
            default => 'Desconhecido'
        };
    }

    /**
     * Obter cor do status
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'yellow',
            self::STATUS_UNDER_REVIEW => 'blue',
            self::STATUS_APPROVED => 'green',
            self::STATUS_REJECTED => 'red',
            default => 'gray'
        };
    }
}