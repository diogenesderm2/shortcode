<?php

namespace App\Models\Admin\Owner;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Admin\Animal\Animal;
use App\Models\Admin\Sample\Sample;
use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'registration',
        'user_id',
        'user_registered',
        'user_updated',
        'user_financial',
        'user_representative',
        'name',
        'address',
        'adress_complement',
        'adress_number',
        'district',
        'city',
        'state',
        'zip_code',
        'rg',
        'cpf',
        'cnpj',
        'state_registration',
        'property',
        'representative_percentage',
        'comments',
        'image',
        'reteste_without_payment',
        'reteste_without_release',
        'valor_identificacao_genetica_bovino',
        'valor_dna_caseira_bovino',
        'valor_kappa_caseina_bovino',
        'valor_beta_lactoglobulina_bovino',
        'valor_identificacao_genetica_equino',
        'valor_identificacao_genetica_caprino',
        'valor_hvip_equino',
        'valor_five_panel_obed_hibrida_hty_equino',
    ];

    protected $casts = [
        'reteste_without_payment' => 'integer',
        'reteste_without_release' => 'integer',
        'representative_percentage' => 'integer',
        'valor_identificacao_genetica_bovino' => 'decimal:2',
        'valor_dna_caseira_bovino' => 'decimal:2',
        'valor_kappa_caseina_bovino' => 'decimal:2',
        'valor_beta_lactoglobulina_bovino' => 'decimal:2',
        'valor_identificacao_genetica_equino' => 'decimal:2',
        'valor_identificacao_genetica_caprino' => 'decimal:2',
        'valor_hvip_equino' => 'decimal:2',
        'valor_five_panel_obed_hibrida_hty_equino' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userRegistered()
    {
        return $this->belongsTo(User::class, 'user_registered');
    }

    public function userUpdated()
    {
        return $this->belongsTo(User::class, 'user_updated');
    }

    public function userFinancial()
    {
        return $this->belongsTo(User::class, 'user_financial');
    }

    public function userRepresentative()
    {
        return $this->belongsTo(User::class, 'user_representative');
    }

    public function animals()
    {
        return $this->hasMany(Animal::class);
    }

    public function samples()
    {
        return $this->hasMany(Sample::class);
    }

    public function phones()
    {
        return $this->hasMany(OwnerPhone::class);
    }

    public function emails()
    {
        return $this->hasMany(OwnerEmail::class);
    }
}
