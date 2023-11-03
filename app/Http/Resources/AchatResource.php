<?php

namespace App\Http\Resources;

use App\Models\Appartement;
use App\Models\Terrain;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AchatResource extends JsonResource
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
            'code' => $this->whenNotNull($this->code),
            'personne_id' => $this->whenNotNull($this->personne_id),
            'bien_id' => $this->whenNotNull($this->bien_id),
            'created_at' => $this->whenNotNull($this->created_at?->format('d-m-Y')),
            'total' => $this->whenNotNull($this->total),
            'reste' => $this->whenLoaded('bien', fn() => $this->bien->cout_achat - $this->total),
            'personne' => $this->whenLoaded('personne', PersonneResource::make($this->whenLoaded('personne'))),
            'bien' => $this->whenLoaded('bien', fn() => match (true) {
                $this->bien instanceof Appartement => AppartementResource::make($this->bien),
                $this->bien instanceof Terrain => TerrainResource::make($this->bien)
            }),
            'paiements' => PaiementResource::collection($this->whenLoaded('paiements')),
        ];
    }
}
