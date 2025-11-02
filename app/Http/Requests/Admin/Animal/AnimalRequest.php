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
            'birth' => 'required|date',
            'genre' => 'required|integer|in:1,2',
            'owner_id' => 'required|exists:owners,id',
        ];

        // Se for uma atualização, permitir o mesmo RG para o animal atual
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            $rules['register'] = [
                'required',
                'string',
                Rule::unique('animals')->ignore($this->animal)
            ];
        } else {
            $rules['register'] = 'required|string|unique:animals,register';
        }

        return $rules;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Converter campos do frontend para o formato do backend
        $data = [];
        
        if ($this->has('rg')) {
            $data['register'] = $this->rg;
        }
        
        if ($this->has('birth_date')) {
            $data['birth'] = $this->birth_date;
        }
        
        if ($this->has('sex')) {
            $data['genre'] = $this->sex === 'macho' ? 1 : 2;
        }
        
        $this->merge($data);
    }
}