<?php

namespace App\Http\Requests\Visite;

use Illuminate\Foundation\Http\FormRequest;

class VisiteRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'personne_id' => 'required',
            'montant' => 'required',
            'appartement_id' => 'required',
        ];
    }
}
