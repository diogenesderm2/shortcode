<?php

namespace App\Http\Requests\Admin\Owner;

use Illuminate\Foundation\Http\FormRequest;

class OwnerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $ownerId = $this->route('owner'); // Pega o ID do proprietário da rota para edição
        
        return [
            'registration' => 'required|integer|unique:owners,registration,' . $ownerId,
            'user_id' => 'required|integer|exists:users,id',
            'name' => 'required|string|max:255',
            'rg' => 'nullable|string|max:20',
            'cpf' => 'nullable|string|max:14',
            'cnpj' => 'nullable|string|max:14',
            'address' => 'nullable|string|max:255',
            'adress_number' => 'nullable|string|max:10',
            'adress_complement' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:2',
            'zip_code' => 'nullable|string|max:10',
            'state_registration' => 'nullable|string|max:20',
            'property_name' => 'nullable|string|max:255',
            'resp_financ' => 'nullable|string|max:255',
            'comments' => 'nullable|string',
            'valor_identificacao_genetica' => 'nullable|string|max:20',
            'valor_beta_caseina' => 'nullable|string|max:20',
            'valor_kappa_caseina' => 'nullable|string|max:20',
            'valor_identificacao_genetica_bovinos' => 'nullable|string|max:20',
            'valor_identificacao_genetica_caprinos' => 'nullable|string|max:20',
            'valor_identificacao_genetica_ovinos' => 'nullable|string|max:20',
            'valor_hvp' => 'nullable|string|max:20',
            'valor_five_panel' => 'nullable|string|max:20',
            // Campos do backend também aceitos
            'valor_identificacao_genetica_bovino' => 'nullable|string|max:20',
            'valor_dna_caseira_bovino' => 'nullable|string|max:20',
            'valor_kappa_caseina_bovino' => 'nullable|string|max:20',
            'valor_beta_lactoglobulina_bovino' => 'nullable|string|max:20',
            'valor_identificacao_genetica_equino' => 'nullable|string|max:20',
            'valor_identificacao_genetica_caprino' => 'nullable|string|max:20',
            'valor_hvip_equino' => 'nullable|string|max:20',
            'valor_five_panel_obed_hibrida_hty_equino' => 'nullable|string|max:20',
            'phones' => 'nullable|array',
            'phones.*.number' => 'required_with:phones|string|max:20',
            'phones.*.type' => 'required_with:phones|string|max:20',
            'emails' => 'nullable|array',
            'emails.*.address' => 'required_with:emails|email|max:255',
            'emails.*.type' => 'required_with:emails|string|max:20',
            'image' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ];
    }
}
