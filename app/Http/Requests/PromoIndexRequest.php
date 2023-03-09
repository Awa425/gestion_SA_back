<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PromoIndexRequest extends FormRequest
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
            'libelle' => 'nullable|string|max:255',
            'date_debut' => 'nullable|date_format:Y-m-d',
            'date_fin_prevue' => 'nullable|date_format:Y-m-d',
            'date_fin_reel' => 'nullable|date_format:Y-m-d',
        ];
    }
    
}