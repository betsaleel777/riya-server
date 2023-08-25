<?php

namespace App\Http\Resources;

use App\Models\Paiement;
use App\Models\Visite;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetteResource extends JsonResource
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
            'origine_type' => str($this->getOrigine())->explode('\\')[2],
            'origine' => $this->whenLoaded('origine', fn () => match (true) {
                $this->origine instanceof Visite => VisiteResource::make($this->origine),
                $this->origine instanceof Paiement => PaiementResource::make($this->origine),
            }),
            'contrat' => $this->when(
                $this->relationLoaded('origine') and $this->origine->relationLoaded('contrat'),
                ContratResource::make($this->origine->contrat)
            ),
        ];
    }
}
