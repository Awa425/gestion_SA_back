<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PresenceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'date_heure_arriver' => $this->date_heure_arriver,
            'apprenants' => ApprenantCollection::make($this->whenLoaded('apprenants')),
        ];
    }
}
