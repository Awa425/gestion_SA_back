<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromoReferentiel extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "promo_id",
        "referentiel_id",
        "is_active",
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];



  public function promo()
    {
        return $this->belongsTo(Promo::class);
    }

    public function referentiel()
    {
        return $this->belongsTo(Referentiel::class);
    }
    
}
