<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EvenementResource extends JsonResource
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
            'subject'=>$this->subject,
            'photo'=>$this->photo,
            'description'=>$this->description,
            'date_debut'=>$this->date_debut,
            'date_fin'=>$this->date_fin,
            'notfication_date'=>$this->notfication_date,
            'event_time'=>$this->event_time,
            'is_active'=>$this->is_active,
            'user_id'=>$this->user_id,
            'presentateur'=>$this->presentateur,
            'referentiels'=> EvenementReferentielResource::collection($this->evenement_referentiels)
                            ->map(function($ref){
                        return $ref->promo_referentiel->referentiel;
            }),
            // 'apprenants'=>$this->presence,
            'apprenants'=>presenceEventResource::collection($this->presence)

        ];
}
}
