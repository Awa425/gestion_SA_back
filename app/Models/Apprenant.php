<?php

namespace App\Models;

use App\Models\Presence;
use App\Mail\NouvelApprenantMail;
use App\Models\ApprenantPresence;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;



class Apprenant extends Authenticatable
{
    use Filterable, HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
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
        'motif',
        'cni',
        'photo',

    ];

    private static $whiteListFilter = [
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
        'cni',

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
        'date_naissance' => 'date:Y-m-d',
        'is_active' => 'boolean',
    ];
    protected $appends = ['decrypted_password'];


    public function toArray()
    {
        $data = parent::toArray();
        $data['id'] = $this->id;
        return $data;
    }

    public function presences()
    {
        return $this->belongsToMany(Presence::class, 'apprenant_presence', 'apprenant_id', 'presence_id');
    }

    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function promoReferentielApprenants()
    {
        return $this->hasMany(PromoReferentielApprenant::class);
    }
    public function absence()
    {
        return $this->hasMany(Absence::class);
    }


    public function promoReferentiels()
    {
        return $this->belongsToMany(PromoReferentiel::class, 'promo_referentiel_apprenants');
    }


    public function insertion()
    {
        return $this->hasMany(InsertionApprenant::class);
    }
}
