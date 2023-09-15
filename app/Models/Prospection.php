<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prospection extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];



    public function insertion()
    {
        return $this->hasMany(InsertionApprenant::class);
    }
}
