<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PromoUpdateRequest extends FormRequest
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
            'libelle' => ['required', 'string', 'max:255'],
            'date_debut' => ['required', 'date'],
            'date_fin_prevue' => ['required', 'date'],
            'date_fin_reel' => ['sometimes', 'date'],    
        ];
    }
    
    public function validatedAndFiltered()
    {
        $allowedFields = ['libelle', 'date_debut', 'date_fin_prevue', 'date_fin_reel'];
        return $this->only($allowedFields);
    }
}