<?php

namespace App\Http\Requests\Achat;

use App\Models\Appartement;
use App\Models\Terrain;
use Illuminate\Foundation\Http\FormRequest;

class AchatPostRequest extends FormRequest
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

        if ($this->filled('bien_id')) {
            $coutAchat = match ($this->bien_type) {
                'Terrain' => Terrain::find($this->bien_id)->cout_achat,
                'Appartement' => Appartement::find($this->bien_id)->cout_achat,
            };
            return [
                'personne_id' => 'required',
                'bien_id' => 'required',
                'montant' => 'required|numeric|not_in:0|lte:' . $coutAchat
            ];
        } else {
            return
                [
                    'personne_id' => 'required',
                    'bien_id' => 'required',
                    'montant' => 'required|numeric|not_in:0'
                ];
        }
    }
}
