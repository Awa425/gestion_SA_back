<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use App\Providers\RoleServiceProvider;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,Filterable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'prenom',
        'photo',
        'adresse',
        'user_id',
        'role_id',
        'email',
        'password',
        'telephone',
        'isActive',
    ];

    private static $whiteListFilter=[
        'matricule',
        'name',
        'prenom',
        'email',
        'date_naissance',
        'telephone',
        'adresse',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function promos() : HasMany
    {
        return $this->hasMany(Promo::class);

    }
    public function referentiels() : HasMany
    {
        return $this->hasMany(Referentiel::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function isAdmin($user): bool
    {
        return $user->role->libelle === RoleServiceProvider::ADMIN;
    }

    public static function isSuperAdmin($user): bool
    {
        return $user->role->libelle === RoleServiceProvider::SUPER_ADMIN;
    }

    public static function isVigile($user): bool
    {
        return $user->role->libelle === RoleServiceProvider::VIGILE;
    }

    public static function isApprenant($user): bool
    {
        return $user->role->libelle === RoleServiceProvider::APPRENANT;
    }

    public static function isMediateurEmploi($user): bool
    {
        return $user->role->libelle === RoleServiceProvider::MEDIATEUR_EMPLOI;
    }

    public static function isVisiteur($user): bool
    {
        return $user->role->libelle === RoleServiceProvider::VISITEUR;
    }


    public function notes()
    {
        return $this->hasMany(Note::class, 'prof_id');
    }
}
