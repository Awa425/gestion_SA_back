<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmploieDuTemp extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function  scopeGetPromoRef(Builder $builder, $ref, $promo){
        return $builder->from('promo_referentiels')->where(['promo_id'=>$promo,'referentiel_id'=>$ref]);
    }
}
