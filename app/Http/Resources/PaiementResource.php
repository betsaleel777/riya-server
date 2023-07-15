<?php

namespace App\Http\Resources;

use App\Models\Achat;
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
            'payable_id' => $this->payable_id,
            'created_at' => $this->created_at->format('d-m-Y'),
            'payable' => $this->whenLoaded('achat', fn () => match (true) {
                $this->payable instanceof Achat => AchatResource::make($this->achat),
            }),
        ];
    }
}
