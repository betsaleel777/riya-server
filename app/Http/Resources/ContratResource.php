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
            'etat' => $this->resource->etat,
            'status' => $this->resource->status,
            'commission' => $this->resource->commission,
            'operation_id' => $this->resource->operation_id,
            'operation_type' => str($this->resource->operation_type)->explode('\\')[2],
            'debut' => $this->resource->debut?->format('d-m-Y'),
            'fin' => $this->resource->fin?->format('d-m-Y'),
            'created_at' => $this->resource->created_at?->format('d-m-Y'),
            'code' => $this->whenLoaded('operation', fn() => $this->resource->operation->code),
            'operation' => $this->whenLoaded('operation', fn() => match (true) {
                $this->resource->operation instanceof Visite => VisiteResource::make($this->resource->operation),
                $this->resource->operation instanceof Achat => AchatResource::make($this->resource->operation),
            }),
        ];
    }
}
