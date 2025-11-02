<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Animal\AnimalType;

class ExamType extends Model
{
    use HasFactory;

    protected $fillable = [
        'animal_type_id',
        'name',
    ];

    public function animalType()
    {
        return $this->belongsTo(AnimalType::class);
    }
}