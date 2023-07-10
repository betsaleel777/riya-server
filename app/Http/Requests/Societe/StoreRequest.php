<?php

namespace App\Http\Requests\Societe;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'raison_sociale' => 'required|max:80',
            'slogan' => 'required|max:155',
            'email' => 'required|max:100',
            'boite_postale' => 'required|max:150',
            'forme_juridique' => 'required|max:50',
            'registre' => 'required|max:180',
            'contact' => 'required|max:15|min:10',
            'siege' => 'required|max:50',
            'image' => 'required|image|mimes:jpeg,png,jpg',
        ];
    }
}
