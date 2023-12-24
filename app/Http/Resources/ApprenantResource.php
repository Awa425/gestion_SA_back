<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Models\PromoReferentielApprenant;
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


        return [
            'id' => $this->id,
            'matricule' => $this->matricule,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'email' => $this->email,
            'date_naissance' => $this->date_naissance->format('Y-m-d'),
            'lieu_naissance' => $this->lieu_naissance,
            'genre' => $this->genre,
            'telephone' => $this->telephone,
            'cni' => $this->cni,
            'motif' => $this->motif,
            'user' => UserResource::make($this->user) ,
            'is_active' => $this->is_active,
            'photo' => base64_encode($this->photo) != null ?  base64_encode($this->photo) : null,
            'promoReferentielId'=> count($this->promoReferentiels)>0 ? $this->promoReferentiels[0]->pivot->promo_referentiel_id : null
        ];
    }
}
