<?php

namespace App\Models\Admin\Sample;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Owner\Owner;
use App\Models\Admin\Animal\Animal;

class Sample extends Model
{
    use HasFactory;

    protected $fillable = [
        'sample_code',
        'owner_id',
        'father_id',
        'mother_id',
        'child_id',
        'sample_type',
        'collection_date',
        'observations',
        'status',
    ];

    protected $casts = [
        'collection_date' => 'date',
    ];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function father()
    {
        return $this->belongsTo(Animal::class, 'father_id');
    }

    public function mother()
    {
        return $this->belongsTo(Animal::class, 'mother_id');
    }

    public function child()
    {
        return $this->belongsTo(Animal::class, 'child_id');
    }
}