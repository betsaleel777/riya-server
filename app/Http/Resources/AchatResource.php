<?php

namespace App\Http\Resources;

use App\Models\Appartement;
use App\Models\Terrain;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

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
            'code' => $this->code,
            'personne_id' => $this->personne_id,
            'bien_id' => $this->bien_id,
            'created_at' => $this->created_at->format('d-m-Y'),
            'total' => $this->whenLoaded('paiements', fn() => $this->paiements->sum('montant')),
            'reste' => $this->when(
                $this->relationLoaded('paiements') and $this->paiements->isNotEmpty() and $this->relationLoaded('bien'),
                fn() => $this->bien->cout_achat - $this->paiements->sum('montant')
            ),
            'personne' => $this->whenLoaded('personne', PersonneResource::make($this->personne)),
            'bien' => $this->whenLoaded('bien', fn() => match (true) {
                $this->bien instanceof Appartement => AppartementResource::make($this->bien),
                $this->bien instanceof Terrain => TerrainResource::make($this->bien)
            }),
            'paiements' => PaiementResource::collection($this->whenLoaded('paiements')),
        ];
    }
}
