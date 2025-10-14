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
            'sample_code' => 'required|string|unique:samples,sample_code',
            'owner_id' => 'required|exists:owners,id',
            'father_id' => 'nullable|exists:animals,id',
            'mother_id' => 'nullable|exists:animals,id',
            'child_id' => 'required|exists:animals,id',
            'sample_type' => 'required|in:sangue,pelo,saliva',
            'collection_date' => 'required|date',
            'observations' => 'nullable|string',
        ];
    }
}