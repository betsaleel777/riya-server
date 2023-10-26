<?php

namespace App\Http\Resources;

use App\Models\Visite;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Visite resource
 */
class VisiteValidationResource extends JsonResource
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
            'code' => $this->code,
            'montant' => $this->resource->getAmountTotaly(),
            'created_at' => $this->resource->created_at->format('d-m-Y'),
            'personne' => $this->whenLoaded('personne', $this->resource->personne->civilite->value . ' ' . $this->resource->personne->nom_complet),
            'bien' => $this->whenLoaded('appartement', $this->resource->appartement->nom),
            'avatar' => $this->when($this->relationLoaded('personne') and $this->personne->relationLoaded('avatar'),
                url($this->resource->personne->avatar?->getUrl())),
        ];
    }
}
