<?php

namespace App\Models\Admin\Animal;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Owner\Owner;
use App\Models\Admin\Sample\Sample;

class Animal extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'rg',
        'birth_date',
        'sex',
        'owner_id',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function samplesAsFather()
    {
        return $this->hasMany(Sample::class, 'father_id');
    }

    public function samplesAsMother()
    {
        return $this->hasMany(Sample::class, 'mother_id');
    }

    public function samplesAsChild()
    {
        return $this->hasMany(Sample::class, 'child_id');
    }
}