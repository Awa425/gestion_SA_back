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
            'date_cours'=>'required | date|after_or_equal:today',
            'heure_debut'=>'required|before:16:00|after_or_equal:08:00',
            'heure_fin'=>'required|before_or_equal:16:00|after:heure_debut',
            'prof_id'=>'required',
        ];
    }
}
