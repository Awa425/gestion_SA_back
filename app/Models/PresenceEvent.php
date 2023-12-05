<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresenceEvent extends Model
{
    use HasFactory;

   protected $guarded=[];


   public function apprenants(){
    return $this->belongsTo(Apprenant::class,'apprenant_id');
   }
   public function event(){
    return $this->belongsTo(Evenement::class,"evenement_id");
   }

}
