<?php

namespace App\Http\Resources;

use App\Models\Achat;
use App\Models\Loyer;
use App\Models\Visite;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetteListResource extends JsonResource
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
            'origine_type' => str($this->getOrigine())->explode('\\')[2],
            'origine_code' => $this->when(
                $this->relationLoaded('origine') and $this->origine->relationLoaded('payable'),
                $this->origine->payable->code
            ),
        ];
    }
}
