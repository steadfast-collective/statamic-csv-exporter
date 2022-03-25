<?php

namespace SteadfastCollective\StatamicCsvExporter\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'collections' => ['required', 'array', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'collections.required' => __('You must select at least one collection.'),
            'collections.min' => __('You must select at least one collection to export.'),
        ];
    }
}
