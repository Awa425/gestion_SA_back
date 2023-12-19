<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Promo;
use Illuminate\Http\Request;
use App\Models\EmploieDuTemp;
use App\Models\PromoReferentiel;
use App\Http\Requests\EmploieDuTempsRequest;
use App\Http\Resources\EmploieDuTempsResource;

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
    public function validerEmploieDutemps($idPromo,$heure_deb,$heure_fin,$promoRefId,$dateCours){
        if (Promo::where('is_active',1)->pluck('id')[0]!=$idPromo) {
            return true;
        }
        $cours = EmploieDuTemp::where(['date_cours'=>$dateCours,'promo_referentiel_id'=>$promoRefId])->get();
        $hrDeb=strtotime($heure_deb);
        $hrFin=strtotime($heure_fin);
        foreach ($cours as $c) {
            if (strtotime($c->heure_debut)==$hrDeb && strtotime($c->heure_fin)==$hrFin ||
            $hrDeb>=strtotime($c->heure_debut) && $hrDeb<strtotime($c->heure_fin) ||
            $hrFin>=strtotime($c->heure_debut) && $hrFin<=strtotime($c->heure_fin) ) {
                return true;
            }
        }
    }
    public function validerJourWeekend($dateCours){
        $date = Carbon::parse($dateCours);
        if ($date->isWeekend()) {
            return true;
        }
    }
    public function store(EmploieDuTempsRequest $request)
    {
        $promoRef= PromoReferentiel::where(['referentiel_id'=>$request->idRef,'promo_id'=> $request->idPromo])->first();

        if ($this->validerEmploieDutemps($request->idPromo,$request->heure_debut,$request->heure_fin,$promoRef->id,
        $request->date_cours)){
             return response ([
                "data"=>[],
                "message"=> "Il y'a déja un cours de prévu à cette heure !"
            ]);
        }
        if ($this->validerJourWeekend($request->date_cours)) {
            return response ([
                "data" => [],
               "message"=> "Impossible de creer un cours dans un jour weekend ! "
            ]);
        }
        $emploieDuTemps= EmploieDuTemp::firstOrCreate([
                'nom_cours'=>$request->nom_cours,
                'date_cours'=>explode('T',$request->date_cours)[0],
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
    public function update(EmploieDuTempsRequest $request, EmploieDuTemp $emploieDuTemp)
    {
        $promoRef= PromoReferentiel::where(['referentiel_id'=>$request->idRef,'promo_id'=> $request->idPromo])->first();
        if (Promo::where('is_active',1)->pluck('id')[0]!=$request->idPromo) {
            return response ([
                "data"=>[],
                "message"=> "Impossible d'insérer un cours pour ce promo !"
            ]);
        }
        $cours = EmploieDuTemp::where(['date_cours'=>$request->date_cours,'promo_referentiel_id'=>$promoRef->id])
                                ->whereNot('id',$request->id)
                                ->get();
        $hrDeb=strtotime($request->heure_debut);
        $hrFin=strtotime($request->heure_fin);

            foreach ($cours as $c) {
                if (strtotime($c->heure_debut)==$hrDeb && strtotime($c->heure_fin)==$hrFin ||
                 $hrDeb>=strtotime($c->heure_debut) && $hrDeb<strtotime($c->heure_fin) ||
                 $hrFin>strtotime($c->heure_debut) && $hrFin<=strtotime($c->heure_fin) ||
                 $hrDeb < strtotime($c->heure_debut) && $hrFin >strtotime($c->heure_fin)) {
                    return response ([
                        "data" => [],
                        "message"=> "Il y'a déja un cours de prévu à cette heure !"
                    ]);
                }
            }

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
