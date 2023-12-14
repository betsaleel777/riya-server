<?php

namespace App\Http\Resources;

use App\Models\Achat;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Achat resource
 */
class AchatValidationResource extends JsonResource
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
            'code' => $this->whenNotNull($this->code),
            'created_at' => $this->whenNotNull($this->resource->created_at?->format('d-m-Y')),
            'bien' => $this->whenLoaded('bien', str($this->resource->bien->nom)->lower()),
            'cout' => $this->whenLoaded('bien', $this->resource->bien->cout_achat),
            'montant' => $this->whenLoaded('pendingPaiement', $this->resource->pendingPaiement->montant),
            'personne' => $this->whenLoaded('personne', str($this->resource->personne->nom_complet)->lower()),
            'avatar' => $this->when($this->relationLoaded('personne') and $this->personne->relationLoaded('avatar'),
                MediaResource::make($this->personne->avatar)),
        ];
    }
}
