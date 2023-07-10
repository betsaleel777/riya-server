<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class AchatListResource extends JsonResource
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
            'created_at' => $this->created_at->format('d-m-Y'),
            'personne' => $this->whenLoaded('personne', fn () => Str::lower($this->personne->nom_complet)),
            'bien' => $this->whenLoaded('bien', fn () => Str::lower($this->bien->nom)),
            'total' => $this->whenLoaded('paiements', fn () => $this->paiements->sum('montant')),
            'reste' => $this->when(
                $this->relationLoaded('paiements') and $this->paiements->isNotEmpty() and $this->relationLoaded('bien'),
                fn () => $this->bien->cout_achat - $this->paiements->sum('montant')
            ),
        ];
    }
}
