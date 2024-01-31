<?php

namespace App\Http\Resources;

use App\Models\Loyer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Loyer resource
 */
class LoyerValidationResource extends JsonResource
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
            'code' => $this->whenNotNull($this->code),
            'cout' => $this->whenNotNull($this->resource->montant),
            'created_at' => $this->whenNotNull($this->resource->created_at?->format('d-m-Y')),
            'montant' => $this->whenLoaded('pendingPaiement', $this->resource->pendingPaiement->montant),
            'personne' => $this->whenLoaded('client', str($this->resource->client->nom_complet)->lower()),
            'bien' => $this->whenLoaded('bien', str($this->resource->bien->nom)->lower()),
            'avatar' => $this->when(
                $this->relationLoaded('client') and $this->client->relationLoaded('avatar'),
                MediaResource::make($this->client->avatar)
            ),
        ];
    }
}
