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
        return [
            'registration' => 'required|integer|unique:owners,registration',
            'user_id' => 'required|integer|exists:users,id',
            'name' => 'required|string|max:255',
            'rg' => 'required|string|max:11',
            'cpf' => 'required|string|max:11',
            'cnpj' => 'required|string|max:14',
            'property' => 'required|string|max:255',
            'image' => 'required|string|max:255',
            'is_active' => 'required|boolean',
        ];
    }
}
