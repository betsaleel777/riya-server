<?php

namespace App\Http\Resources;

use App\Models\Achat;
use App\Models\Visite;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContratResource extends JsonResource
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
            'fin' => $this->fin->format('d-m-Y'),
            'created_at' => $this->created_at->format('d-m-Y'),
            'code' => $this->whenLoaded('operation', fn () => $this->operation->code),
            'operation' => $this->whenLoaded('operation', fn () => match (true) {
                $this->operation instanceof Visite => VisiteResource::make($this->operation),
                $this->operation instanceof Achat => AchatResource::make($this->operation),
            }),
        ];
    }
}
