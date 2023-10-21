<?php

namespace App\Http\Resources;

use App\Models\Depense;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Depense $resource
 */
class DepenseListResource extends JsonResource
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
            'titre' => $this->resource->titre,
            'status' => $this->resource->status,
            'montant' => $this->resource->montant,
            'created_at' => $this->resource->created_at->format('d-m-Y'),
            'type' => $this->whenLoaded('type', str($this->resource->type->nom)->lower()),
        ];
    }
}
