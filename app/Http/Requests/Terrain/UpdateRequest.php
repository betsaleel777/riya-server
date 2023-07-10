<?php

namespace App\Http\Requests\Terrain;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'nom' => 'required|unique:terrains,nom,' . $this->route('terrain')->id,
            'ville' => 'required',
            'pays' => 'required',
            'quartier' => 'required',
            'superficie' => 'required|numeric',
            'montant_location' => 'required|numeric',
            'montant_investit' => 'required|numeric',
            'cout_achat' => 'required|numeric',
            'proprietaire_id' => 'required',
        ];
    }
}
