<?php

namespace App\Http\Requests\Achat;

use App\Models\Achat;
use App\Models\Loyer;
use Illuminate\Foundation\Http\FormRequest;

class PaiementRequest extends FormRequest
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
        $value = match ($this->payable_type) {
            'Achat' => Achat::with('bien', 'paiements')->find($this->payable_id)->reste(),
            'Loyer' => Loyer::find($this->payable_id)->montant,
        };
        return [
            'montant' => 'required|numeric|not_in:0|lte:' . $value,
        ];
    }
}
