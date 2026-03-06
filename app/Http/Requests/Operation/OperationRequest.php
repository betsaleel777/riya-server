<?php

namespace App\Http\Requests\Operation;

use App\Enums\ContratState;
use App\Models\Contrat;
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
                    $visite = Visite::with(['appartement', 'contrat'])->find($value);

                    if (!$visite || !$visite->appartement) {
                        return;
                    }

                    // Vérifier s'il existe un contrat EN COURS pour cet appartement (hors la visite actuelle)
                    $query = Contrat::whereHasMorph('operation', [Visite::class], function ($query) use ($visite) {
                        $query->where('appartement_id', $visite->appartement_id);
                    })->where('etat', ContratState::USING);

                    // Exclure le contrat de cette visite si il existe
                    if ($visite->contrat) {
                        $query->where('id', '!=', $visite->contrat->id);
                    }

                    if ($query->exists()) {
                        $fail("L'appartement {$visite->appartement->nom} est déjà occupé par un contrat en cours. Impossible de continuer le processus pour cette visite.");
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
