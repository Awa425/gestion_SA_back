<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReferentielResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'libelle' => $this->libelle,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'userid' => $this->userid,
            'user' => UserResource::make($this->whenLoaded('user')),
            'promos' => PromoCollection::make($this->whenLoaded('promos')),
        ];
    }
}
