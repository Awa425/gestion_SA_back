<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PromoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'libelle' => $this->libelle,
            'annee' => $this->annee,
            'is_active' => $this->is_active,
            'user' => UserResource::make($this->whenLoaded('user')),
            'referentiels' => ReferentielCollection::make($this->whenLoaded('referentiels')),
        ];
    }
}
