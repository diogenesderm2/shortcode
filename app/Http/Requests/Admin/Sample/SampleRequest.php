<?php

namespace App\Http\Requests\Admin\Sample;

use Illuminate\Foundation\Http\FormRequest;

class SampleRequest extends FormRequest
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
            'exam_id' => 'required|exists:exam_types,id',
            'billing_type' => 'required|exists:billing_types,id',
            'animal_id' => 'required|exists:animals,id',
            'owner_id' => 'required|exists:owners,id',
            'sample_type_id' => 'required|exists:sample_types,id',
            'responsible_collect' => 'nullable|string|max:255',
            'value' => 'nullable|numeric|min:0',
            'collected_at' => 'nullable|date',
            'external_registry' => 'nullable|string|max:255',
            
            // Campos do Test
            'test_type_id' => 'required|exists:test_types,id',
            'father_id' => 'nullable|exists:animals,id',
            'mother_id' => 'nullable|exists:animals,id',
            'child_id' => 'nullable|exists:animals,id',
            'is_technique' => 'nullable|boolean',
            'comments' => 'nullable|string',
        ];
    }
}