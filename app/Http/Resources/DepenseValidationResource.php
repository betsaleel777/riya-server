<?php

namespace App\Http\Resources;

use App\Models\Depense;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Depense resource
 */
class DepenseValidationResource extends JsonResource
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
            'montant' => $this->resource->montant,
            'titre' => $this->resource->titre,
            'created_at' => $this->resource->created_at->format('d-m-Y'),
            'type' => $this->whenLoaded('type', $this->resource->type->nom),
            'audit' => AuditResource::make($this->whenLoaded('audit')),
        ];
    }
}
