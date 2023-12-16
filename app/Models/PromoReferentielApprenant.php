<?php

namespace App\Models;

use App\Models\Referentiel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PromoReferentielApprenant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "promo_referentiel_id",
        "apprenant_id",
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    public function promoReferentiel()
    {
        return $this->belongsTo(PromoReferentiel::class);
    }

    public function apprenant()
    {
        return $this->belongsTo(Apprenant::class);
    }

    public function referentiel(): BelongsTo
    {
        return $this->belongsTo(Referentiel::class, 'referentiel_id');
    }
}
