<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class EvenementRequest extends FormRequest
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
            "subject"=>'required',
            // Rule::unique('evenements')->where(function ($query) {
            //     return $query->where('event_date', $this->validationData()['event_date']);
            // }),
            "description"=>"required",
            "event_date"=>"required | date|after_or_equal:". Carbon::now()->format('Y-m-d'),
            "event_time"=>"required"
        ];
    }
}
