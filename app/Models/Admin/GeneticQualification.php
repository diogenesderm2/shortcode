<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class GeneticQualification extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_id',
        'status',
        'type',
        'confidence_score',
        'details',
        'notes',
        'calculated_by',
        'calculated_at',
    ];

    protected $casts = [
        'details' => 'array',
        'confidence_score' => 'decimal:2',
        'calculated_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Status constants
    const STATUS_QUALIFIED = 'qualified';
    const STATUS_NOT_QUALIFIED = 'not_qualified';
    const STATUS_INCONCLUSIVE = 'inconclusive';
    const STATUS_PENDING = 'pending';

    // Type constants
    const TYPE_PERMANENT_ARCHIVE = 'permanent_archive';
    const TYPE_PARENTAGE = 'parentage';
    const TYPE_PATERNITY = 'paternity';
    const TYPE_MATERNITY = 'maternity';
    const TYPE_GENERAL = 'general';

    /**
     * Get all available statuses
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_QUALIFIED => 'Qualificado',
            self::STATUS_NOT_QUALIFIED => 'Não Qualificado',
            self::STATUS_INCONCLUSIVE => 'Inconclusivo',
            self::STATUS_PENDING => 'Pendente',
        ];
    }

    /**
     * Get all available types
     */
    public static function getTypes(): array
    {
        return [
            self::TYPE_PERMANENT_ARCHIVE => 'Arquivo Permanente',
            self::TYPE_PARENTAGE => 'Parentesco',
            self::TYPE_PATERNITY => 'Paternidade',
            self::TYPE_MATERNITY => 'Maternidade',
            self::TYPE_GENERAL => 'Geral',
        ];
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return self::getStatuses()[$this->status] ?? 'Desconhecido';
    }

    /**
     * Get type label
     */
    public function getTypeLabelAttribute(): string
    {
        return self::getTypes()[$this->type] ?? 'Desconhecido';
    }

    /**
     * Get status color for UI
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_QUALIFIED => 'green',
            self::STATUS_NOT_QUALIFIED => 'red',
            self::STATUS_INCONCLUSIVE => 'yellow',
            self::STATUS_PENDING => 'gray',
            default => 'gray'
        };
    }

    /**
     * Check if qualification is complete
     */
    public function isComplete(): bool
    {
        return $this->status !== self::STATUS_PENDING;
    }

    /**
     * Relationship with Test
     */
    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    /**
     * Relationship with User (who calculated)
     */
    public function calculatedBy()
    {
        return $this->belongsTo(User::class, 'calculated_by');
    }
}