<?php

namespace App\Models\Admin\Owner;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OwnerPhone extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'number',
        'type',
    ];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }
}