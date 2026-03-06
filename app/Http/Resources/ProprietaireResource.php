<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ProprietaireResource extends JsonResource
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
            'nom_complet' => Str::lower($this->nom_complet),
            'telephone' => $this->telephone,
            'email' => $this->email,
            'cni' => $this->cni,
            'created_at' => $this->whenNotNull($this->created_at?->format('d-m-Y')),
        ];
    }
}
