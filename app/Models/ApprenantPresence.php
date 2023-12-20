<?php

namespace App\Models;

use App\Models\Presence;
use App\Models\Apprenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class ApprenantPresence extends Model
{
    use HasFactory, HasTimestamps;


    protected $fillable = [
        'apprenant_id',
        'presence_id',
    ];

    public function apprenant()
    {
        return $this->belongsTo(Apprenant::class)->with(['referentiels']);
    }

    public function presence()
    {
        return $this->belongsTo(Presence::class);
    }

    public function referentiels(): BelongsToMany
    {
        return $this->belongsToMany(Referentiel::class, 'promo_referentiel_apprenants', 'apprenant_id', 'promo_referentiel_id')
            ->withTimestamps()
            ->withPivot('created_at');
    }
}
