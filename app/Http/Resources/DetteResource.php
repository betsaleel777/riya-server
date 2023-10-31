<?php

namespace App\Http\Resources;

use App\Models\Dette;
use App\Models\Paiement;
use App\Models\Visite;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Dette resource
 */
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
            'id' => $this->resource->id,
            'code' => $this->whenNotNull($this->resource->code),
            'montant' => $this->whenNotNull($this->resource->montant),
            'status' => $this->whenNotNull($this->resource->status),
            'created_at' => $this->whenNotNull($this->resource->created_at?->format('d-m-Y')),
            'origine_type' => $this->whenLoaded('origine', str($this->getOrigine())->explode('\\')[2]),
            'origine' => $this->whenLoaded('origine', fn() => match (true) {
                $this->origine instanceof Visite => VisiteResource::make($this->origine),
                $this->origine instanceof Paiement => PaiementResource::make($this->origine),
            }),
            'contrat' => $this->whenLoaded('origine', fn() => match (true) {
                $this->origine instanceof Visite and $this->origine->relationLoaded('contrat') =>
                ContratResource::make($this->origine->contrat),
                $this->origine instanceof Paiement and $this->origine->relationLoaded('payable') and
                $this->origine->payable->relationLoaded('contrat') => ContratResource::make($this->origine->payable->contrat),
                default => null
            }),
        ];
    }
}
