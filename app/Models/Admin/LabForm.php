<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class LabForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_number',
        'sample_positions',
        'form_data',
        'user_created',
        'generated_at',
    ];

    protected $casts = [
        'sample_positions' => 'array',
        'form_data' => 'array',
        'generated_at' => 'datetime',
    ];

    /**
     * Relacionamento com o usuário que criou o formulário
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_created');
    }

    /**
     * Gerar número único do formulário
     */
    public static function generateFormNumber()
    {
        do {
            $number = 'FORM-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } while (self::where('form_number', $number)->exists());

        return $number;
    }
}