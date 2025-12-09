<?php

namespace App\Http\Requests\Operation;

use App\Models\Visite;
use Illuminate\Foundation\Http\FormRequest;

class OperationRequest extends FormRequest
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
            'mois' => 'required|numeric',
            'visite_id' => [
                'required',
                'numeric',
                'exists:visites,id',
                function ($attribute, $value, $fail) {
                    $visite = Visite::with('appartement')->find($value);

                    if (!$visite) {
                        return;
                    }

                    // Vérifier si l'appartement est déjà occupé
                    if ($visite->appartement && $visite->appartement->isBusy()) {
                        $fail("L'appartement {$visite->appartement->nom} est déjà occupé par une autre location. Impossible de continuer le processus pour cette visite.");
                    }
                }
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'visite_id.exists' => 'La visite sélectionnée n\'existe pas.',
            'mois.required' => 'Le nombre de mois est requis.',
            'mois.numeric' => 'Le nombre de mois doit être un nombre.',
            'mois.min' => 'Le nombre de mois doit être au moins 1.',
        ];
    }
}
