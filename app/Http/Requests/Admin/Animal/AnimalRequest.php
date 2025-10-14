<?php

namespace App\Http\Requests\Admin\Animal;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
            'name' => 'required|string|max:255',
            'rg' => 'required|string|unique:animals,rg',
            'birth_date' => 'required|date',
            'sex' => 'required|in:macho,femea',
            'owner_id' => 'required|exists:owners,id',
        ];
    }
}