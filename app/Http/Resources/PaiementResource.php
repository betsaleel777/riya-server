<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'id' => $this->id,
            'code' => $this->code,
            'montant' => $this->montant,
            'status' => $this->status,
            'achat_id' => $this->achat_id,
            'created_at' => $this->created_at->format('d-m-Y'),
            'achat' => $this->whenLoaded('achat', fn () => AchatResource::make($this->achat)),
        ];
    }
}
