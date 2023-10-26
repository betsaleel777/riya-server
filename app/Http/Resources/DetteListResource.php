<?php

namespace App\Http\Resources;

use App\Models\Dette;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Dette resource
 */
class DetteListResource extends JsonResource
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
            'status' => $this->resource->status,
            'created_at' => $this->resource->created_at->format('d-m-Y'),
            'origine_type' => str($this->getOrigine())->explode('\\')[2],
            'origine_code' => $this->when($this->relationLoaded('origine'), $this->origine->code),
        ];
    }
}
