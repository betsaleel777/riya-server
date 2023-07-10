<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppartementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reference' => $this->reference,
            'nom' => $this->nom,
            'ville' => $this->ville,
            'pays' => $this->pays,
            'quartier' => $this->quartier,
            'observation' => $this->observation,
            'attestation_villageoise' => (bool)$this->attestation_villageoise,
            'titre_foncier' => (bool)$this->titre_foncier,
            'document_cession' => (bool)$this->document_cession,
            'arreter_approbation' => (bool)$this->arreter_approbation,
            'cours_commune' => (bool)$this->cours_commune,
            'etage' => (bool)$this->etage,
            'placard' => (bool)$this->placard,
            'toilette' => (bool)$this->toilette,
            'cuisine' => (bool)$this->cuisine,
            'garage' => (bool)$this->garage,
            'parking' => (bool)$this->parking,
            'cie' => (bool)$this->cie,
            'sodeci' => (bool)$this->sodeci,
            'cloture' => (bool)$this->cloture,
            'superficie' => $this->superficie,
            'montant_location' => $this->montant_location,
            'montant_investit' => $this->montant_investit,
            'cout_achat' => $this->cout_achat,
            'proprietaire_id' => $this->proprietaire_id,
            'type_appartement_id' => $this->type_appartement_id,
            'proprietaire' => $this->whenLoaded('proprietaire', fn () => $this->proprietaire),
            'type' => $this->whenLoaded('type', fn () => $this->type),
        ];
    }
}
