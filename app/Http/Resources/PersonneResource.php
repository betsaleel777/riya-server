<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonneResource extends JsonResource
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
            'nom_complet' => $this->nom_complet,
            'telephone' => $this->telephone,
            'email' => $this->email,
            'cni' => $this->cni,
            'lieu_naissance' => $this->lieu_naissance,
            'date_naissance' => $this->date_naissance,
            'nationalite' => $this->nationalite,
            'ville' => $this->ville,
            'quartier' => $this->quartier,
            'pays' => $this->pays,
            'animal' => $this->animal,
            'fonctions' => $this->fonctions,
            'civilite' => $this->civilite,
            'created_at' => $this->created_at->format('d-m-Y'),
            'type_client_id' => $this->type_client_id,
            'type' => $this->whenLoaded('type', fn () => $this->type),
            'piece' => $this->whenLoaded('piece', fn () => url($this->piece->getUrl())),
            'avatar' => $this->whenLoaded('avatar', fn () => url($this->avatar->getUrl())),
        ];
    }
}
