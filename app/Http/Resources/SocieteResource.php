<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SocieteResource extends JsonResource
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
            'raison_sociale' => $this->raison_sociale,
            'slogan' => $this->slogan,
            'email' => $this->email,
            'boite_postale' => $this->boite_postale,
            'forme_juridique' => $this->forme_juridique,
            'registre' => $this->registre,
            'contact' => $this->contact,
            'siege' => $this->siege,
            'description' => $this->description,
            'frais_dossier' => $this->frais_dossier,
            'logo' => $this->whenLoaded('logo', fn() => url($this->logo->getUrl())),
        ];
    }
}
