<?php

namespace App\Http\Resources;

use App\Models\Achat;
use App\Models\Contrat;
use App\Models\Visite;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Contrat resource
 */
class ContratResource extends JsonResource
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
            'etat' => $this->whenNotNull($this->resource->etat),
            'status' => $this->whenNotNull($this->resource->status),
            'commission' => $this->whenNotNull($this->resource->commission),
            'operation_id' => $this->whenNotNull($this->resource->operation_id),
            'operation_type' => $this->whenNotNull(str($this->resource->operation_type)->explode('\\')[2]),
            'debut' => $this->whenNotNull($this->resource->debut?->format('d-m-Y')),
            'fin' => $this->whenNotNull($this->resource->fin?->format('d-m-Y')),
            'created_at' => $this->whenNotNull($this->resource->created_at?->format('d-m-Y')),
            'code' => $this->whenLoaded('operation', fn() => $this->resource->operation->code),
            'operation' => $this->whenLoaded('operation', fn() => match (true) {
                $this->resource->operation instanceof Visite => VisiteResource::make($this->resource->operation),
                $this->resource->operation instanceof Achat => AchatResource::make($this->resource->operation),
            }),
        ];
    }
}
