<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OperationResource extends JsonResource
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
            'visite_id' => $this->visite_id,
            'mois' => $this->mois,
            'visite' => VisiteResource::make($this->whenLoaded('visite')),
        ];
    }
}
