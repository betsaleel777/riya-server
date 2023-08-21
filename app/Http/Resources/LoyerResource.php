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
            'code' => $this->code,
            'montant' => $this->montant,
            'status' => $this->status,
            'created_at' => $this->created_at->format('d-m-Y'),
            'contrat' => ContratResource::make($this->whenLoaded('contrat')),
            'client' => PersonneResource::make($this->whenLoaded('client')),
            'bien' => AppartementResource::make($this->whenLoaded('bien')),
        ];
    }
}
