<?php

namespace App\Http\Resources;

use App\Models\Achat;
use App\Models\Visite;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ContratListResource extends JsonResource
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
            'etat' => $this->etat,
            'status' => $this->status,
            'commission' => $this->commission,
            'operation_id' => $this->operation_id,
            'operation_type' => str($this->operation_type)->explode('\\')[2],
            'debut' => $this->debut->format('d-m-Y'),
            'fin' => $this->when(!empty($this->fin), fn () => $this->fin->format('d-m-Y')),
            'code' => $this->whenLoaded('operation', fn () => $this->operation->code),
            'client' => $this->when(
                $this->relationLoaded('operation') and $this->operation->relationLoaded('personne'),
                fn () => Str::lower($this->operation->personne->nom_complet)
            ),
            'bien' => $this->whenLoaded('operation', fn () => match (true) {
                $this->operation instanceof Visite => Str::lower($this->operation->appartement->nom),
                $this->operation instanceof Achat => Str::lower($this->operation->bien->nom),
            }),
        ];
    }
}
