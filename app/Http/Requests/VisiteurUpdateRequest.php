<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VisiteurUpdateRequest extends FormRequest
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
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],

            'INE' => ['required' , 'regex:/^([0-9]*)$/' ],

            'motif' => ['required', 'string', 'max:255'],
        ];
    }
    public function validatedAndFiltered()
    {
        $allowedFields = ['nom', 'prenom', 'INE', 'motif'];
        return $this->only($allowedFields);
    }
}
