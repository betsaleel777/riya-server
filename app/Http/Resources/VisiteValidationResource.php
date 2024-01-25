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
            'personne' => PersonneResource::make($this->whenLoaded('personne')),
            'bien' => $this->whenLoaded('appartement', $this->resource->appartement->nom),
        ];
    }
}
