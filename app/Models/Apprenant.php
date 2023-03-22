<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;


class Apprenant extends Model
{
    use Filterable, HasApiTokens, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'matricule',
        'nom',
        'prenom',
        'email',
        'password',
        'date_naissance',
        'lieu_naissance',
        'telephone',
        'user_id',
        'genre',
        'is_active',
        'reserves',

    ];

    private static $whiteListFilter=[
        'matricule',
        'nom',
        'prenom',
        'email',
        'password',
        'date_naissance',
        'lieu_naissance',
        'telephone',
        'user_id',
        'genre',
        'reserves',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'date_naissance' => 'date',
        'is_active' => 'boolean',
    ];

    public function presence(): HasOne
    {
        return $this->hasOne(Presence::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function promoReferentielApprenants()
{
    return $this->hasMany(PromoReferentielApprenant::class);
}


public function promoReferentiels()
    {
        return $this->belongsToMany(PromoReferentiel::class);
    }



}
