<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InsertionRequest extends FormRequest
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
            //
            'apprenant_id' => 'required|integer',
            'prospection_id' => 'required|integer',
            'profil' => 'required|string',
            'status' => 'required|string',
            'type_contrat' => 'required|string',
            'renumeration' => 'required|string',
            'date_debut' => 'required|string',
        ];
    }
}
