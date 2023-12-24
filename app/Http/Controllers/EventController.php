<?php

namespace App\Http\Controllers;

use App\Http\Requests\EvenementRequest;
use App\Http\Resources\EvenementResource;
use App\Jobs\verifyEventComingJob;
use App\Models\Apprenant;
use App\Models\Promo;
use App\Models\Evenement;
use App\Models\EvenementReferentiel;
use Illuminate\Http\Request;
use App\Models\PromoReferentiel;
use App\Models\PromoReferentielApprenant;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use function PHPSTORM_META\map;

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
    public function controlEvent($dateDebut,$dateFin, $titleEvent){
        $events= Evenement::all();
        foreach ($events as $event) {
            if ($event->date_debut==$dateDebut && $event->date_fin==$dateFin && $event->subject==$titleEvent) {
                return true;
            }
        }

    }
    public function store(EvenementRequest $request)
    {     
        $promoActive=Promo::where('is_active',1)->first();
        $idsPromoReferentiel=[];
        if ($request->referentiels_id) {
            $idsPromoReferentiel= PromoReferentiel::where('promo_id',$promoActive->id)
                                ->whereIn('referentiel_id',$request->referentiels_id)
                                ->pluck('id');
        }
        if ($this->controlEvent($request->date_debut,$request->date_fin, $request->subject)) {
            return response ("Impossible");
        }
        return DB::transaction( function () use($request, $idsPromoReferentiel) {
            $notDate=Carbon::parse($request->date_debut)->subDays(3)->format('Y-m-d');
            $event= Evenement::firstOrCreate([
                 'subject'=>$request->subject,
                 'photo'=>$request->photo,
                 'description'=>$request->description,
                 'date_debut'=>$request->date_debut,
                 'date_fin'=>$request->date_fin,
                 'notfication_date'=>$notDate,
                 'event_time'=>$request->event_time,
                 'user_id'=>$request->user_id,
                 'presentateur'=>$request->presentateur,
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
        $idsPromoReferentiel=[];
        if ($request->referentiels_id) {
            $idsPromoReferentiel= PromoReferentiel::where('promo_id',$promoActive->id)
                                ->whereIn('referentiel_id',$request->referentiels_id)
                                ->pluck('id');
        }
        $event->update($request->only(
            "subject", "photo", "description", "date_debut","date_fin","notfication_date",'event_time','presentateur'));

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
    public function annulerEvent($idEvent){
        Evenement::annuleOrRestoreEvent($idEvent,0);
    }
    public function restoreEvent($idEvent){
        Evenement::annuleOrRestoreEvent($idEvent,1);
    }

}
