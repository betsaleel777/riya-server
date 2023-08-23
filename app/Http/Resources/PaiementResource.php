<?php

namespace App\Http\Resources;

use App\Models\Achat;
use App\Models\Loyer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

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
            'payable' => $this->whenLoaded('payable', fn () => match (true) {
                $this->payable instanceof Achat => AchatResource::make($this->payable),
                $this->payable instanceof Loyer => LoyerResource::make($this->payable),
            }),
            'payable_type' => Str::of($this->payable_type)->explode('\\')[2],
        ];
    }
}
