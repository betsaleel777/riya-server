<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class VisiteListResource extends JsonResource
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
            'created_at' => $this->created_at->format('d-m-Y'),
            'loyer' => $this->whenLoaded('appartement', $this->appartement->montant_location),
            'personne' => $this->whenLoaded('personne', fn () => $this->personne->nom_complet),
            'appartement' => $this->whenLoaded('appartement', Str::lower($this->appartement)),
            'caution' => $this->whenLoaded('caution', fn () => $this->caution->mois) ?? 0,
            'avance' => $this->whenLoaded('avance', fn () => $this->avance->mois) ?? 0,
            'frais' => $this->whenLoaded('frais', fn () => $this->frais->mois) ?? 0,
            'avanceStatus' => $this->statusAvance(),
        ];
    }
}
