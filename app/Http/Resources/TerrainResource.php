<?php

namespace App\Http\Resources;

use App\Models\Terrain;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Terrain resource
 */
class TerrainResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'reference' => $this->whenNotNull($this->resource->reference),
            'nom' => $this->whenNotNull($this->resource->nom),
            'ville' => $this->whenNotNull($this->resource->ville),
            'pays' => $this->whenNotNull($this->resource->pays),
            'quartier' => $this->whenNotNull($this->resource->quartier),
            'attestation_villageoise' => $this->whenNotNull($this->resource->attestation_villageoise),
            'titre_foncier' => $this->whenNotNull($this->resource->titre_foncier),
            'document_cession' => $this->whenNotNull($this->resource->document_cession),
            'arreter_approbation' => $this->whenNotNull($this->resource->arreter_approbation),
            'superficie' => $this->whenNotNull($this->resource->superficie),
            'montant_location' => $this->whenNotNull($this->resource->montant_location),
            'montant_investit' => $this->whenNotNull($this->resource->montant_investit),
            'cout_achat' => $this->whenNotNull($this->resource->cout_achat),
            'proprietaire_id' => $this->whenNotNull($this->resource->proprietaire_id),
            'type_appartement_id' => $this->whenNotNull($this->resource->type_appartement_id),
            'proprietaire' => $this->whenLoaded('proprietaire', fn() => $this->resource->proprietaire),
            'type' => $this->whenLoaded('type', fn() => $this->resource->type),
        ];
    }
}
