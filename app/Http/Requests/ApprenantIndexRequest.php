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

   
}