<?php

namespace App\Models\Admin\Sample;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Admin\Owner\Owner;
use App\Models\Admin\Animal\Animal;
use App\Models\User;

class Sample extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'old_id',
        'exam_id',
        'billing_type',
        'animal_id',
        'owner_id',
        'sample_type_id',
        'responsible_collect',
        'responsible_collect_id',
        'user_registered',
        'user_updated',
        'user_released',
        'user_representative',
        'is_technique',
        'is_default',
        'is_released',
        'priority',
        'show_client',
        'send_again',
        'external_registry',
        'file_name',
        'value',
        'representative_percentage',
        'is_marked',
        'uploaded_at',
        'released_at',
        'collected_at',
        'added_spreadsheet_at',
        'external_sample_date',
        'canceled_at',
        'is_sent',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'released_at' => 'datetime',
        'collected_at' => 'datetime',
        'added_spreadsheet_at' => 'datetime',
        'external_sample_date' => 'datetime',
        'canceled_at' => 'datetime',
        'is_marked' => 'boolean',
        'value' => 'decimal:2',
        'representative_percentage' => 'float',
    ];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    public function responsibleCollect()
    {
        return $this->belongsTo(User::class, 'responsible_collect_id');
    }

    public function userRegistered()
    {
        return $this->belongsTo(User::class, 'user_registered');
    }

    public function userUpdated()
    {
        return $this->belongsTo(User::class, 'user_updated');
    }

    public function userReleased()
    {
        return $this->belongsTo(User::class, 'user_released');
    }

    public function userRepresentative()
    {
        return $this->belongsTo(User::class, 'user_representative');
    }

    public function examType()
    {
        return $this->belongsTo(\App\Models\Admin\ExamType::class, 'exam_id');
    }

    public function billingType()
    {
        return $this->belongsTo(\App\Models\Admin\BillingType::class, 'billing_type');
    }
}