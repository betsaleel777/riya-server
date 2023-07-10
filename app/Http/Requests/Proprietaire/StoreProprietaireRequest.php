<?php

namespace App\Http\Requests\Proprietaire;

use Illuminate\Foundation\Http\FormRequest;

class StoreProprietaireRequest extends FormRequest
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
            'nom_complet' => 'required|max:150',
            'telephone' => 'required|unique:proprietaires,telephone|max:15',
            'email' => 'nullable|email|unique:proprietaires,email',
            'cni' => 'required|max:20|unique:proprietaires,cni',
        ];
    }
}
