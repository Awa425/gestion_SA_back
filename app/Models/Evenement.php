<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Evenement extends Model
{
    use HasFactory;

    protected $fillable=[
        'subject',
        'description',
        'event_date',
        'event_time',
        'photo',
        'motification_date',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function referentiels()
    {
        return $this->belongsToMany(Referentiel::class,'evenement_referentiels','evenement_id', 'promo_referentiel_id');
    }
    public function scopeIdsPromoRef(Builder $builder)
    {
        $promoActive= $builder->from('promos')
                       ->where('is_active',1)
                       ->first() ;
       return $builder->from('promo_referentiels')->where('promo_id',$promoActive->id);
                       
        
    }
}
