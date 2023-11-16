<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmploieDuTempsRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'nom_cours'=>'required',
            'date_cours'=>'required | date|after:today',
            'heure_debut'=>'required|date_format:H:i|after:08:00|before:16:00',
            'heure_fin'=>'required|date_format:H:i|before_or_equal:16:00|after:heure_debut',
            'prof_id'=>'required',
            'promo_referentiel_id'=>''
        ];
    }
}
