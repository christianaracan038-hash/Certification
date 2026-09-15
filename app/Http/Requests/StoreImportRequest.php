<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreImportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:csv,xlsx,xls', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'file.mimes' => 'The file must be a CSV, XLS, or XLSX file.',
            'file.max' => 'The file may not be larger than 10MB.',
        ];
    }
}