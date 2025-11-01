<?php

namespace App\Http\Resources\Admin\Owner;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OwnerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'registration' => $this->registration,
            'image' => $this->image,
            'property_name' => $this->property, // Mapear property para property_name para manter compatibilidade com frontend
            'name' => $this->name,
            'rg' => $this->rg,
            'cpf' => $this->cpf,
            'cnpj' => $this->cnpj,
            'address' => $this->address,
            'adress_number' => $this->adress_number,
            'adress_complement' => $this->adress_complement,
            'district' => $this->district,
            'city' => $this->city,
            'state' => $this->state,
            'zip_code' => $this->zip_code,
            'state_registration' => $this->state_registration,
            'resp_financ' => $this->resp_financ,
            'comments' => $this->comments,
            'valor_identificacao_genetica' => $this->valor_identificacao_genetica_bovino,
            'valor_beta_caseina' => $this->valor_beta_lactoglobulina_bovino,
            'valor_kappa_caseina' => $this->valor_kappa_caseina_bovino,
            'valor_identificacao_genetica_bovinos' => $this->valor_identificacao_genetica_bovino,
            'valor_identificacao_genetica_caprinos' => $this->valor_identificacao_genetica_caprino,
            'valor_identificacao_genetica_ovinos' => $this->valor_identificacao_genetica_equino,
            'valor_hvp' => $this->valor_hvip_equino,
            'valor_five_panel' => $this->valor_five_panel_obed_hibrida_hty_equino,
            'phones' => $this->phones ? $this->phones->map(function($phone) {
                return [
                    'id' => $phone->id,
                    'number' => $phone->number,
                    'type' => $phone->type,
                ];
            }) : [],
            'emails' => $this->emails ? $this->emails->map(function($email) {
                return [
                    'id' => $email->id,
                    'address' => $email->address,
                    'type' => $email->type,
                ];
            }) : [],
            'created_at' => $this->created_at,
        ];
    }
}
