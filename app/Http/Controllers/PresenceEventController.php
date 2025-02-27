<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use App\Models\Apprenant;
use Illuminate\Http\Request;
use App\Models\PresenceEvent;
use App\Models\PromoReferentiel;
use App\Models\PromoReferentielApprenant;
use App\Http\Resources\presenceEventResource;

class PresenceEventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return presenceEventResource::collection(PresenceEvent::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $presenceEvent= PresenceEvent::firstOrCreate([
            'apprenant_id'=>$request->apprenant_id ,
            'evenement_id'=>$request->evenement_id,
            'nom'=>$request->nom,
            "prenom"=>$request->prenom,
            "email"=>$request->email,
            "telephone"=>$request->telephone,
            "cni"=>$request->cni,
            "sexe"=>$request->sexe,
            'is_present'=>0
        ]);
        return new presenceEventResource($presenceEvent);

    }
    public function marquerPresenceApp(Request $request, $idEvent){
        // return $idEvent;
        foreach ($request->presenceEvent as $element) {
            $idsPromoRefApps[]=PromoReferentielApprenant::where(["promo_referentiel_id"=>$element["promoRefId"],
                                 "apprenant_id"=>$element["apprenant_id"]])->first()->id;
        }
        PresenceEvent::whereIn("promo_referentiel_apprenant_id",$idsPromoRefApps)
                    ->where("evenement_id",$idEvent)
                    ->update(['isPresent' => 1]);
    }

    /**
     * Display the specified resource.
     */
    public function show(PresenceEvent $presenceEvent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PresenceEvent $presenceEvent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PresenceEvent $presenceEvent)
    {
        //
    }
}
