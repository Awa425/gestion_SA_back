<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class Promo extends Model
{
    use Filterable;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'libelle',
        'date_debut',
        'date_fin_prevue',
        'date_fin_reel',
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
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function promoReferentielApprenants()
    {
        return $this->hasMany(PromoReferentielApprenant::class);
    }
    
    public function referentiels()
    {
        return $this->belongsToMany(Referentiel::class, 'promo_referentiel_apprenants');
    }
    
    public function apprenants()
    {
        return $this->belongsToMany(Apprenant::class, 'promo_referentiel_apprenants');
    }
    
}

