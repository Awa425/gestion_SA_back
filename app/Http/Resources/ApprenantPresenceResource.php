<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\ApprenantResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ApprenantPresenceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'created_at' => $this->pivot->created_at,
            'apprenant' => new ApprenantResource($this),

        ];
    }
}
