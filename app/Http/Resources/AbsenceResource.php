<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\ApprenantResource;
use Illuminate\Http\Resources\Json\JsonResource;


class AbsenceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
{
    return [
        'id' => $this->id,
        'date_absence' => $this->date_absence,
        'justifier' => $this->justifier,
        'motif' => $this->motif,
        'apprenant' => ApprenantResource::make($this->apprenant),
];
}

}
