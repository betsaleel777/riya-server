<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property User resource
 */
class UserResource extends JsonResource
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
            'name' => $this->whenNotNull($this->name),
            'email' => $this->whenNotNull($this->email),
            'created_at' => $this->whenNotNull($this->created_at?->format('d-m-Y')),
            'photo' => $this->whenLoaded('photo', fn() => $this->photo?->getUrl()),
            'roles' => $this->getRoleNames(),
        ];
    }
}
