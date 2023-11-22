<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'montant' => $this->whenNotNull($this->montant),
            'code' => $this->whenNotNull($this->code),
            'appartement_id' => $this->whenNotNull($this->appartement_id),
            'personne_id' => $this->whenNotNull($this->personne_id),
            'status' => $this->whenNotNull($this->status),
            'frais_dossier' => $this->whenNotNull($this->frais_dossier),
            'created_at' => $this->whenNotNull($this->created_at?->format('d-m-Y')),
            'date_expiration' => $this->whenNotNull($this->date_expiration?->format('d-m-Y')),
            'personne' => PersonneResource::make($this->whenLoaded('personne')),
            'appartement' => AppartementResource::make($this->whenLoaded('appartement')),
            'caution' => $this->whenLoaded('caution', fn() => $this->caution->mois) ?? 0,
            'avance' => $this->whenLoaded('avance', fn() => $this->avance->mois) ?? 0,
            'frais' => $this->whenLoaded('frais', fn() => $this->frais->mois) ?? 0,
            'cautionObject' => $this->whenLoaded('caution', fn() => $this->caution),
            'avanceObject' => $this->whenLoaded('avance', fn() => $this->avance),
            'fraisObject' => $this->whenLoaded('frais', fn() => $this->frais),
            'responsable' => $this->when($this->relationLoaded('responsable') and $this->responsable->relationLoaded('user'), UserResource::make($this->responsable->user)),
        ];
    }
}
