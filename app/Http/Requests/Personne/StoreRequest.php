<?php

namespace App\Http\Requests\Personne;

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
            'email' => 'nullable|email|unique:personnes,email',
            'nom_complet' => 'required|max:180',
            'cni' => 'required|alpha_num|unique:personnes,cni',
            'lieu_naissance' => 'required',
            'date_naissance' => 'required',
            'nationalite' => 'required',
            'telephone' => 'required|max:15|unique:personnes,telephone',
            'ville' => 'required',
            'quartier' => 'required|max:60',
            'pays' => 'required|max:20',
            'fonctions' => 'required',
            'image_piece' => 'required|image|mimes:jpeg,png,jpg',
            'image_avatar' => 'nullable|image|mimes:jpeg,png,jpg',
        ];
    }
}
