<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class presenceEventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this->id,
            "apprenant"=>new PromoReferentielApprenantResource($this->apprenants),
            "isPresent"=>$this->isPresent
            // "evenement"=>$this->event
        ];
    }
}
