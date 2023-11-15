<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvenementReferentiel extends Model
{
    use HasFactory;


    protected $fillable=[
        'event_id',
        'promo_refentiel_id',
    ];
    public function promo_referentiel()
    {
        return $this->belongsTo(PromoReferentiel::class);
    } 
}
