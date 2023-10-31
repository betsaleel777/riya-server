<?php

namespace App\Http\Resources;

use App\Models\Achat;
use App\Models\Loyer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Paiement resource
 */
class PaiementResource extends JsonResource
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
            'payable_id' => $this->whenNotNull($this->resource->payable_id),
            'created_at' => $this->whenNotNull($this->resource->created_at?->format('d-m-Y')),
            'payable' => $this->whenLoaded('payable', fn() => match (true) {
                $this->payable instanceof Achat => AchatResource::make($this->payable),
                $this->payable instanceof Loyer => LoyerResource::make($this->payable),
            }),
            'payable_type' => $this->whenNotNull(str($this->resource->payable_type)->explode('\\')[2]),
        ];
    }
}
