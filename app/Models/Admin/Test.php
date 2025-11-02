<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Admin\Sample\Sample;
use App\Models\Admin\Animal\Animal;
use App\Models\User;

class Test extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sample_id',
        'dad_id',
        'mom_id',
        'type_id',
        'user_registered',
        'responsible_collect',
        'is_technique',
        'is_default',
        'is_retest',
        'central_result',
        'comments',
        'is_read',
    ];

    protected $casts = [
        'is_technique' => 'boolean',
        'is_default' => 'boolean',
        'is_retest' => 'boolean',
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relacionamento com Sample
     */
    public function sample()
    {
        return $this->belongsTo(Sample::class);
    }

    /**
     * Relacionamento com Animal (pai)
     */
    public function father()
    {
        return $this->belongsTo(Animal::class, 'dad_id');
    }

    /**
     * Relacionamento com Animal (mãe)
     */
    public function mother()
    {
        return $this->belongsTo(Animal::class, 'mom_id');
    }

    /**
     * Relacionamento com TestType
     */
    public function testType()
    {
        return $this->belongsTo(TestType::class, 'type_id');
    }

    /**
     * Relacionamento com User (usuário que registrou)
     */
    public function userRegistered()
    {
        return $this->belongsTo(User::class, 'user_registered');
    }
}