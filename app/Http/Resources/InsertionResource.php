<?php

namespace App\Http\Resources;

use App\Models\Prospection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InsertionResource extends JsonResource
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
            'apprenant' => ApprenantResource::make($this->apprenant),
            'prospection' =>ProspectionResource::make($this->prospection),
            'profil' => $this->profil,
            'status' => $this->status,
            'typeContrat' => $this->typeContrat,
            'renumeration' => $this->renumeration,
            'date_debut' => $this->date_debut,
        ];
    }
}
