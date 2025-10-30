<?php

namespace App\Http\Requests\Admin\Animal;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AnimalRequest extends FormRequest
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
        $rules = [
            'name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'sex' => 'required|in:macho,femea',
            'owner_id' => 'required|exists:owners,id',
        ];

        // Se for uma atualização, permitir o mesmo RG para o animal atual
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['rg'] = [
                'required',
                'string',
                Rule::unique('animals')->ignore($this->animal)
            ];
        } else {
            $rules['rg'] = 'required|string|unique:animals,rg';
        }

        return $rules;
    }
}