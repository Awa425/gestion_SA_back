<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProspectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' =>$this->id,
            'entreprise' =>$this->entreprise,
            'responsable' => $this->responsable,
            'fonction' =>$this->fonction,
            'telephone' =>$this->telephone,
            'email' =>$this->email,
            'adresse' =>$this->adresse,
            'commentaire' =>$this->commentaire,
            'date' =>$this->date,
            'nbre' =>$this->nbre,
            'is_active' =>$this->is_active,
        ];
    }
}
