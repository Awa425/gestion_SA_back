<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApprenantUpdateRequest extends FormRequest
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
            'email' => ['required', 'email', 'max:255'],
            'password' => ['sometimes', 'regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',],
            'date_naissance' => ['required', 'date'],
            'lieu_naissance' => ['nullable', 'string', 'max:255'],
            'telephone' => ['required' , 'regex:/^([0-9\s\-\+\(\)]*)$/' ],
            'cni' => ['required' , 'regex:/^([0-9]*)$/' ],
            'reserves' => ['nullable'],
            'motif' => ['nullable'],
            'photo' => ['nullable'],
            'genre' => ['required', 'in:Masculin,Feminin'],

        ];
    }

    public function validatedAndFiltered()
    {
        $allowedFields = ['nom', 'prenom', 'email', 'password', 'date_naissance', 'lieu_naissance', 'genre', 'telephone','cni','photo','reserves','motif'];
        return $this->only($allowedFields);
    }
}
