<?php

namespace App\Http\Resources;
use App\Models\PromoReferentielApprenant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\PromoReferentielApprenantResource;

class ApprenantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

             $apprenant = $this->resource;

            $promoReferentielApprenant = PromoReferentielApprenant::where('apprenant_id', '=', $apprenant->id)->with('promo', 'referentiel')->first();
           
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'email' => $this->email,
            'password' => $this->password,
            'date_naissance' => $this->date_naissance,
            'lieu_naissance' => $this->lieu_naissance,
            'telephone' => $this->telephone,
            'user' => $this->user_id,
            'is_active' => $this->is_active,
            'promo' => $promoReferentielApprenant->promo,
            'referentiel' => $promoReferentielApprenant->referentiel,
        ];
    }
}
