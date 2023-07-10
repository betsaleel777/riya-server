<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TerrainListResource extends JsonResource
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
            'nom' => $this->nom,
            'quartier' => $this->quartier,
            'status' => $this->status,
            'montant_location' => $this->montant_location,
            'proprietaire' => $this->whenLoaded('proprietaire', fn () => $this->proprietaire->nom_complet),
            'type' => $this->whenLoaded('type', fn () => $this->type->nom),
            'created_at' => $this->created_at->format('d-m-Y'),
        ];
    }
}
