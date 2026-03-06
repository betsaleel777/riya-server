<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'total' => $this->total ?? 0,
            'code' => $this->whenNotNull($this->code),
            'created_at' => $this->whenNotNull($this->created_at?->format('d-m-Y')),
            'personne' => $this->whenLoaded('personne', fn() => str($this->personne->nom_complet)->lower()),
            'bien' => $this->whenLoaded('bien', str($this->bien->nom)->lower()),
            'reste' => $this->whenLoaded('bien', function () {
                $coutAchat = $this->relationLoaded('contrat') && $this->contrat?->cout_achat
                    ? $this->contrat->cout_achat
                    : $this->bien->cout_achat;
                return $coutAchat - ($this->total ?? 0);
            }),
        ];
    }
}
