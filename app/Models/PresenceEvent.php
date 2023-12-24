<?php

namespace App\Models;

use App\Models\Apprenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PresenceEvent extends Model
{
    use HasFactory;

   protected $guarded=[];


   public function apprenant(){
    return $this->belongsTo(Apprenant::class);
   }
   public function event(){
    return $this->belongsTo(Evenement::class,"evenement_id");
   }

}
