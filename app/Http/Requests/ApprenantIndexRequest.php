<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApprenantIndexRequest extends FormRequest
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
    public function rules()
    {
        return [
            'nom' => 'nullable|string',
            'prenom' => 'nullable|string',
            'email' => 'nullable|email',
            'date_naissance' => 'nullable|date_format:Y-m-d',
            'lieu_naissance' => 'nullable|string',
            'telephone' => 'nullable|string',
            'genre' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'nom.string' => 'Le nom doit être une chaîne de caractères.',
            'prenom.string' => 'Le prénom doit être une chaîne de caractères.',
            'email.email' => 'L\'adresse email doit être valide.',
            'date_naissance.date_format' => 'La date de naissance doit être au format YYYY-MM-DD.',
            'lieu_naissance.string' => 'Le lieu de naissance doit être une chaîne de caractères.',
            'genre.string' => 'Le genre doit etre M ou F.',
            'telephone.string' => 'Le numéro de téléphone doit être une chaîne de caractères.',
        ];
    }
}