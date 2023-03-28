<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReferentielStoreRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'libelle' => ['required', 'string', 'max:255', 'unique:referentiels,libelle'],
            'description' => ['nullable', 'string'],
        ];
    }
}
