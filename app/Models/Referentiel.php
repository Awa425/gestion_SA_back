<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class Referentiel extends Model
{
    use HasFactory;
    use Filterable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'libelle',
        'description',
        'is_active',
        'user_id',
    ];

    private static $whiteListFilter=[
        'libelle',
        'date_debut',
        'date_fin_prevue',
        'date_fin_reel',
        'user_id',

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'is_active' => 'boolean',
        'user_id' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
   
    public static function getPromosByReferentielId($referentielId)
    {
        $referentiel = self::find($referentielId);
        return $referentiel->promos;
    }

    public function promoReferentielApprenants()
{
    return $this->hasMany(PromoReferentielApprenant::class);
}

public function promos()
{
    return $this->belongsToMany(Promo::class, 'promo_referentiel_apprenants');
}

public function apprenants()
{
    return $this->belongsToMany(Apprenant::class, 'promo_referentiel_apprenants');
}

}
