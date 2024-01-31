<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoyerResource extends JsonResource
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
            'montant' => $this->whenNotNull($this->montant),
            'paid' => $this->whenNotNull($this->paid, 0),
            'status' => $this->whenNotNull($this->status),
            'created_at' => $this->whenNotNull($this->created_at?->format('d-m-Y')),
            'contrat' => ContratResource::make($this->whenLoaded('contrat')),
            'personne' => PersonneResource::make($this->whenLoaded('client')),
            'bien' => AppartementResource::make($this->whenLoaded('bien')),
            'paiements' => PaiementResource::collection($this->whenLoaded('paiements')),
        ];
    }
}
