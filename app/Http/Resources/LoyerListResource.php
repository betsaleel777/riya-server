<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class LoyerListResource extends JsonResource
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
            'paid' => $this->whenNotNull((int) $this->paid),
            'pending' => $this->whenNotNull($this->pending),
            'created_at' => $this->created_at->format('d-m-Y'),
            'client' => $this->whenLoaded('client', fn() => Str::lower($this->client->nom_complet)),
            'bien' => $this->whenLoaded('bien', fn() => Str::lower($this->bien->nom)),
        ];
    }
}
