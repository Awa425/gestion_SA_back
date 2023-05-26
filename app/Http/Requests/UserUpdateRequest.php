<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:191'],
            'prenom' => ['required', 'string', 'max:191'],
            'date_naissance' => ['nullable', 'string', 'max:191'],
            'matricule' => ['nullable', 'string', 'max:191'],
            'email' => ['required', 'email', 'max:191'],
            'password' => ['nullable'],
            'photo' => ['nullable'],
            'telephone' => ['nullable', 'string', 'max:191'],
            'email_verified_at' => ['nullable'],
            'user_id' => ['nullable', 'integer'],
            'adresse' => ['nullable', 'string', 'max:191'],
            'role_id' => ['nullable', 'integer'],
            'remember_token' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function validatedAndFiltered()
    {
        $allowedFields = ['name', 'prenom', 'email', 'password', 'date_naissance', 'adresse', 'role_id', 'telephone'];
        return $this->only($allowedFields);
    }
}
