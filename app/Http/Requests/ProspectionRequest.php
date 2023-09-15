<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProspectionRequest extends FormRequest
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
            'entreprise' =>'required|string',
            'responsable' =>'required|string',
            'fonction' =>'required|string',
            'telephone' =>'required|string',
            'email' =>'required|string',
            'adresse' =>'required|string',
            'commentaire' =>'required|string',
            'date' =>'required|string',
            'nbre' =>'sometimes|integer',
        ];
    }
}
