<?php

namespace App\Http\Resources;

use App\Models\Appartement;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Appartement resource
 */
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
            'reference' => $this->whenNotNull($this->reference),
            'nom' => $this->whenNotNull(str($this->nom)->lower()),
            'ville' => $this->whenNotNull($this->ville),
            'pays' => $this->whenNotNull($this->pays),
            'quartier' => $this->whenNotNull(str($this->quartier)),
            'observation' => $this->observation,
            'attestation_villageoise' => $this->whenNotNull($this->attestation_villageoise),
            'titre_foncier' => $this->whenNotNull($this->titre_foncier),
            'document_cession' => $this->whenNotNull($this->document_cession),
            'arreter_approbation' => $this->whenNotNull($this->arreter_approbation),
            'cours_commune' => $this->whenNotNull($this->cours_commune),
            'etage' => $this->whenNotNull($this->etage),
            'placard' => $this->whenNotNull($this->placard),
            'toilette' => $this->whenNotNull($this->toilette),
            'cuisine' => $this->whenNotNull($this->cuisine),
            'garage' => $this->whenNotNull($this->garage),
            'parking' => $this->whenNotNull($this->parking),
            'cie' => $this->whenNotNull($this->cie),
            'sodeci' => $this->whenNotNull($this->sodeci),
            'cloture' => $this->whenNotNull($this->cloture),
            'superficie' => $this->whenNotNull($this->superficie),
            'montant_location' => $this->whenNotNull($this->montant_location),
            'montant_investit' => $this->whenNotNull($this->montant_investit),
            'cout_achat' => $this->whenNotNull($this->cout_achat),
            'proprietaire_id' => $this->whenNotNull($this->proprietaire_id),
            'type_appartement_id' => $this->whenNotNull($this->type_appartement_id),
            'proprietaire' => $this->whenLoaded('proprietaire', fn() => $this->proprietaire),
            'type' => $this->whenLoaded('type', fn() => $this->type),
        ];
    }
}
