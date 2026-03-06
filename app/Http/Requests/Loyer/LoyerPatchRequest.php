<?php

namespace App\Http\Requests\Loyer;

use App\Models\Loyer;
use Illuminate\Foundation\Http\FormRequest;

class LoyerPatchRequest extends FormRequest
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
        $loyer = Loyer::withSum('paiements as paid', 'montant')->find($this->query('id'));
        return [
            'montant' => 'required|numeric|not_in:0|lte:' . $loyer->montant - $loyer->paid,
        ];
    }
}
