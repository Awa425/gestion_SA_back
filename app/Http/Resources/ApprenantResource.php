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
            'date_naissance' => $this->date_naissance,
            'lieu_naissance' => $this->lieu_naissance,
            'genre' => $this->genre,
            'telephone' => $this->telephone,
            'user' => $this->user_id,
            'is_active' => $this->is_active,
        ];
    }
}
