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
            'nom_complet' => $this->whenNotNull($this->nom_complet),
            'telephone' => $this->whenNotNull($this->telephone),
            'email' => $this->whenNotNull($this->email),
            'cni' => $this->whenNotNull($this->cni),
            'lieu_naissance' => $this->whenNotNull($this->lieu_naissance),
            'date_naissance' => $this->whenNotNull($this->date_naissance),
            'nationalite' => $this->whenNotNull($this->nationalite),
            'ville' => $this->whenNotNull($this->ville),
            'quartier' => $this->whenNotNull($this->quartier),
            'pays' => $this->whenNotNull($this->pays),
            'animal' => $this->whenNotNull($this->animal),
            'fonctions' => $this->whenNotNull($this->fonctions),
            'civilite' => $this->whenNotNull($this->civilite),
            'created_at' => $this->whenNotNull($this->created_at?->format('d-m-Y')),
            'type_client_id' => $this->whenNotNull($this->type_client_id),
            'type' => $this->whenLoaded('type', fn() => $this->type),
            'piece' => $this->whenLoaded('piece', fn() => url($this->piece->getUrl())),
            'avatar' => $this->whenLoaded('avatar', fn() => url($this->avatar->getUrl())),
        ];
    }
}
