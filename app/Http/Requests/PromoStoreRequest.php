<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PromoStoreRequest extends FormRequest
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
            'libelle' => ['required', 'string', 'max:255', 'unique:promos,libelle'],
            'date_debut' => ['required', 'date'],
            'date_fin_prevue' => ['required', 'date'],
            'date_fin_reel' => ['sometimes', 'date'],
        ];
    }
    public function messages()
{
    return [
        'libelle.required' => 'Le nom de la promo est obligatoire.',
        'libelle.string' => 'Le nom de la promo doit être une chaîne de caractères.',
        'libelle.max' => 'Le nom de la promo ne doit pas dépasser :max caractères.',
        'libelle.unique' => 'Le nom de la promo existe déjà.',
        'date_debut.required' => 'La date de début est obligatoire.',
        'date_debut.date' => 'La date de début doit être une date valide.',
        'date_fin_prevue.required' => 'La date de fin prévue est obligatoire.',
        'date_fin_prevue.date' => 'La date de fin prévue doit être une date valide.',
        'date_fin_reel.date' => 'La date de fin réelle doit être une date valide.',
    ];
}
    public function validatedAndFiltered()
    {
        $allowedFields = ['libelle', 'date_debut', 'date_fin_prevue', 'date_fin_reel'];
        return $this->only($allowedFields);
    }
}