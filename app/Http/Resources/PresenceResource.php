<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\ApprenantResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ApprenantPresenceResource;

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
        'apprenants' => ApprenantPresenceResource::collection($this->whenLoaded('apprenants')),
];
}

}
