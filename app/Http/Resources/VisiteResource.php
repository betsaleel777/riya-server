<?php

namespace App\Http\Resources;

use App\Models\Appartement;
use App\Models\Terrain;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class VisiteResource extends JsonResource
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
            'montant' => $this->montant,
            'code' => $this->code,
            'created_at' => $this->created_at->format('d-m-Y'),
            'date_expiration' => $this->date_expiration->format('d-m-Y'),
            'appartement_id' => $this->appartement_id,
            'personne_id' => $this->personne_id,
            'status' => $this->status,
            'personne' => PersonneResource::make($this->whenLoaded('personne')),
            'appartement' => AppartementResource::make($this->whenLoaded('appartement')),
            'caution' => $this->whenLoaded('caution', fn () => $this->caution->mois) ?? 0,
            'avance' => $this->whenLoaded('avance', fn () => $this->avance->mois) ?? 0,
            'frais' => $this->whenLoaded('frais', fn () => $this->frais->mois) ?? 0,
            'cautionObject' => $this->whenLoaded('caution', fn () => $this->caution),
            'avanceObject' => $this->whenLoaded('avance', fn () => $this->avance),
            'fraisObject' => $this->whenLoaded('frais', fn () => $this->frais),
        ];
    }
}
