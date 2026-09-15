<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdateImportMappingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'mapping' => ['required', 'array'],
            'mapping.*' => ['nullable', 'string', 'in:name,email,event_date'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $targets = collect($this->input('mapping', []))->filter()->values();

            if ($targets->filter(fn ($t) => $t === 'name')->count() !== 1) {
                $validator->errors()->add('mapping', 'Exactly one column must be mapped to Name.');
            }

            if ($targets->filter(fn ($t) => $t === 'email')->count() !== 1) {
                $validator->errors()->add('mapping', 'Exactly one column must be mapped to Email.');
            }

            if ($targets->duplicates()->isNotEmpty()) {
                $validator->errors()->add('mapping', 'Each field can only be mapped to one column.');
            }
        });
    }
}