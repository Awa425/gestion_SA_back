<?php

namespace App\Http\Resources;
use App\Models\Promo;
use App\Models\Referentiel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PromoReferentielResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
    

        return [
        
            'promo' => Promo::make($this->promo->toArray()),
            'referentiel' => Referentiel::make($this->referentiel->toArray()),

        ];
    }
}
