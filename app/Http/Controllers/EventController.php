<?php

namespace App\Http\Controllers;

use App\Http\Resources\EvenementResource;
use App\Models\Promo;
use App\Models\Evenement;
use Illuminate\Http\Request;
use App\Models\PromoReferentiel;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return EvenementResource::collection(Evenement::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $promoActive=Promo::where('is_active',1)->first();
        $idsPromoReferentiel= PromoReferentiel::where('promo_id',$promoActive->id)
                            ->whereIn('referentiel_id',$request->referentiels_id)
                            ->pluck('id');
 
        return DB::transaction( function () use($request, $idsPromoReferentiel) {

            $event= Evenement::firstOrCreate([
                 'subject'=>$request->subject,
                 'photo'=>$request->photo,
                 'description'=>$request->description,
                 'event_date'=>$request->event_date,
                 'notfication_date'=>$request->notfication_date,
                 'event_time'=>$request->event_time,
                 'user_id'=>$request->user_id,
                 'is_active'=>1
             ]);
             $event->referentiels()->attach($idsPromoReferentiel);
             return new EvenementResource($event);

        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Evenement $event)
    {
        if ($event) {
            return EvenementResource::make($event);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Evenement $event)
    {
        $promoActive=Promo::where('is_active',1)->first();
        $idsPromoReferentiel= PromoReferentiel::where('promo_id',$promoActive->id)
                            ->whereIn('referentiel_id',$request->referentiels_id)
                            ->pluck('id');
        $event->update($request->only(
            "subject", "photo", "description", "event_date","notfication_date",'event_time'));

        $event->referentiels()->sync($idsPromoReferentiel);
        return new EvenementResource($event);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Evenement $event)
    {
        if ($event) {
            $event->delete();
            return new EvenementResource($event);
        }
    }
}
