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
class DetteValidationResource extends JsonResource
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
            'code' => $this->resource->code,
            'montant' => $this->resource->montant,
            'created_at' => $this->resource->created_at->format('d-m-Y'),
            'bien' => $this->whenLoaded('origine', match (true) {
                $this->resource->origine instanceof Paiement => str($this->resource->origine->payable->bien->nom)->lower(),
                $this->resource->origine instanceof Visite => str($this->resource->origine->appartement->nom)->lower(),
            }),
            'proprietaire' => $this->whenLoaded('origine', match (true) {
                $this->resource->origine instanceof Paiement =>
                str($this->resource->origine->payable->bien->proprietaire->nom_complet)->lower(),
                $this->resource->origine instanceof Visite => str($this->resource->origine->appartement->proprietaire->nom_complet)->lower(),
            }),
            'telephone' => $this->whenLoaded('origine', match (true) {
                $this->resource->origine instanceof Paiement => str($this->resource->origine->payable->bien->proprietaire->telephone)->lower(),
                $this->resource->origine instanceof Visite => str($this->resource->origine->appartement->proprietaire->telephone)->lower(),
            }),
        ];
    }
}
