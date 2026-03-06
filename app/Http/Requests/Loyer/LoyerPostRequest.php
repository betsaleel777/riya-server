<?php

namespace App\Http\Requests\Loyer;

use Illuminate\Foundation\Http\FormRequest;

class LoyerPostRequest extends FormRequest
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
            'contrat_id' => 'required',
            'periode' => 'required|array',
        ];
    }
}
