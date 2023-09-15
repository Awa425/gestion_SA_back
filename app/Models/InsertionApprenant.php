<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsertionApprenant extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];



    public function apprenant()
    {
        return $this->belongsTo(Apprenant::class);
    }


    public function prospection()
    {
        return $this->belongsTo(Prospection::class);
    }
}
