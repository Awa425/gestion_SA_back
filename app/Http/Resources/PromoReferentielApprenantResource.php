<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PromoReferentielApprenantResource extends JsonResource
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
            'promo_id' => $this->promo_id,
            'referentiel_id' => $this->referentiel_id,
            'apprenant_id' => $this->apprenant_id,
            //'apprenants' => new ApprenantResource($this->apprenants),
            //'promo_referentiel_apprenants' =>new ApprenantResource($this->apprenants),

        ];
    }
}
