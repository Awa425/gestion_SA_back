<?php

namespace App\Models;

use App\Models\Apprenant;
use App\Models\ApprenantPresence;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Presence extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date_heure_arriver'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'date_heure_arriver' => 'datetime',
    ];

    public function apprenants()
    {
        return $this->belongsToMany(Apprenant::class)
                    ->withTimestamps()
                    ->withPivot('created_at');
    }
    


}
