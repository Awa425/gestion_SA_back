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
            'date_debut' => $this->date_debut,
            'date_fin_prevue' => $this->date_fin_prevue,
            'date_fin_reel' => $this->date_fin_reel,
            //'user' => UserResource::make($this->whenLoaded('user')),
            'referentiels' => ReferentielCollection::make($this->whenLoaded('referentiels')),
        ];
    }
}