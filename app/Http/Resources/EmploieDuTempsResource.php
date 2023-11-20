<?php

namespace App\Http\Resources;

use App\Models\PromoReferentiel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmploieDuTempsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "nom_cours"=>$this->nom_cours,
            "date_cours"=>$this->date_cours,
            "heure_debut"=>$this->heure_debut,
            "heure_fin"=>$this->heure_fin,
            "idRef"=>PromoReferentiel::where('id',$this->promo_referentiel_id)->pluck('referentiel_id')[0],
        ];
    }
}
