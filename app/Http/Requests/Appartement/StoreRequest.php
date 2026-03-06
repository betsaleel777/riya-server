<?php

namespace App\Http\Requests\Appartement;

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
            'nom' => 'required|unique:terrains,nom',
            'ville' => 'required',
            'pays' => 'required',
            'quartier' => 'required',
            'superficie' => 'required|numeric|not_in:0',
            'montant_location' => 'required|numeric|not_in:0',
            'montant_investit' => 'required|numeric|not_in:0',
            'cout_achat' => 'required|numeric',
            'proprietaire_id' => 'required',
        ];
    }
}
