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
        $today = date('Ymd');
        $prefix = 'FORM-' . $today . '-';
        
        // Buscar o último número do dia
        $lastForm = self::where('form_number', 'LIKE', $prefix . '%')
            ->orderBy('form_number', 'desc')
            ->first();
        
        if ($lastForm) {
            // Extrair o número sequencial do último formulário
            $lastNumber = (int) substr($lastForm->form_number, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            // Primeiro formulário do dia
            $nextNumber = 1;
        }
        
        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}