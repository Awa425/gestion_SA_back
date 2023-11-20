<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmploieDuTempsRequest;
use App\Http\Resources\EmploieDuTempsResource;
use App\Models\EmploieDuTemp;
use App\Models\Promo;
use App\Models\PromoReferentiel;
use Illuminate\Http\Request;

class EmploieDuTempController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return EmploieDuTempsResource::collection(EmploieDuTemp::all());
    }
    public function getCoursByIdRefAndIdPromo($idRef, $idPromo){
        
        $promoRef= PromoReferentiel::where(['promo_id'=>$idPromo,'referentiel_id'=>$idRef])->first();
        // $promoRef= EmploieDuTemp::getPromoRef($idRef, $idPromo)->first();
        return EmploieDuTempsResource::collection(EmploieDuTemp::where('promo_referentiel_id',$promoRef->id)->get());
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(EmploieDuTempsRequest $request)
    {
        if (Promo::where('is_active',1)->pluck('id')[0]!=$request->idPromo) {
            return ("Impossible pour ce promo !");
        }
        $cours = EmploieDuTemp::where('date_cours',$request->date_cours)->get();
        $hrDeb=strtotime($request->heure_debut);
        $hrFin=strtotime($request->heure_fin);
        foreach ($cours as $c) {
            if (strtotime($c->heure_debut)==$hrDeb && strtotime($c->heure_fin)==$hrFin ||
             $hrDeb>strtotime($c->heure_debut) && $hrDeb<strtotime($c->heure_fin) ) {
                return("Il y'a dejà un cours de prévu à cet heure ! ");
            }
        }
        $promoRef= PromoReferentiel::where(['referentiel_id'=>$request->idRef,'promo_id'=> $request->idPromo])->first();

        $emploieDuTemps= EmploieDuTemp::firstOrCreate([
                'nom_cours'=>$request->nom_cours,
                'date_cours'=>$request->date_cours,
                'heure_debut'=>$request->heure_debut,
                'heure_fin'=>$request->heure_fin,
                'prof_id'=>$request->prof_id,
                'promo_referentiel_id'=>$promoRef->id
        ]);

        return new EmploieDuTempsResource($emploieDuTemps);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, EmploieDuTemp $emploieDuTemp)
    {
      
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmploieDuTemp $emploieDuTemp)
    {
        $emploieDuTemp->update($request->only('nom_cours','date_cours','heure_debut','heure_fin'));
        return new EmploieDuTempsResource($emploieDuTemp);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmploieDuTemp $emploieDuTemp)
    {
        if ($emploieDuTemp) {
            $emploieDuTemp->delete();
            return new EmploieDuTempsResource($emploieDuTemp);
        }
    }
}
