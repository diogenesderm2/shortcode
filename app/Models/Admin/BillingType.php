<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Admin\Animal\AnimalType;

class BillingType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'animal_type_id',
        'type',
    ];

    public function animalType()
    {
        return $this->belongsTo(AnimalType::class);
    }
}