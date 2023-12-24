<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            "apprenant"=>ApprenantResource::make($this->apprenant),
            "is_present"=>$this->is_present,
            "nom"=>$this->nom,
            "prenom"=>$this->prenom,
            "email"=>$this->email,
            "telephone"=>$this->telephone,
            "genre"=>$this->genre,
        ];
    }
}
