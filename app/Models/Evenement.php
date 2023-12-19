<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evenement extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable=[
        'subject',
        'description',
        'date_debut',
        'date_fin',
        'event_time',
        'photo',
        'notfication_date',
        'user_id',
        'is_active',
        'presentateur'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function referentiels()
    {
        return $this->belongsToMany(Referentiel::class,'evenement_referentiels','evenement_id',
        'promo_referentiel_id')->withPivot('promo_referentiel_id');
    }
    public function evenement_referentiels()
    {
        return $this->hasMany(EvenementReferentiel::class);
    }
    public function scopeIdsPromoRef(Builder $builder)
    {
        $promoActive= $builder->from('promos')
                       ->where('is_active',1)
                       ->first() ;
       return $builder->from('promo_referentiels')->where('promo_id',$promoActive->id);
        
    }
    public function scopeAnnuleOrRestoreEvent(Builder $builder,$idEvent,$etat){
        return $builder->where('id',$idEvent)
                       ->update(['is_active'=>$etat]);
    }
    public function presence(){
        return $this->hasMany(PresenceEvent::class);
    }
}
