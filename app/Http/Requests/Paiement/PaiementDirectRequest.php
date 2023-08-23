<?php

namespace App\Http\Requests\Paiement;

use App\Models\Achat;
use App\Models\Loyer;
use Illuminate\Foundation\Http\FormRequest;

class PaiementDirectRequest extends FormRequest
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
        if ($this->filled('payable_type') and $this->filled('payable_id')) {
            $value = match ($this->payable_type) {
                'Achat' => Achat::with('bien', 'paiements')->find($this->payable_id)->reste(),
                'Loyer' => Loyer::find($this->payable_id)->montant,
            };
            return [
                'payable_type' => 'bail|required',
                'payable_id' => 'bail|required',
                'montant' => 'required|numeric|not_in:0|lte:' . $value,
            ];
        } else {
            return [
                'payable_type' => 'required',
                'payable_id' => 'required',
            ];
        }
    }
}
