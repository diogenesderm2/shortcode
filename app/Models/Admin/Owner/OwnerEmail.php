<?php

namespace App\Models\Admin\Owner;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OwnerEmail extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'address',
        'type',
    ];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }
}