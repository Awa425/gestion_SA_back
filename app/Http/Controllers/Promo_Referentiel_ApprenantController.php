<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use App\Models\Referentiel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\PromoReferentiel;
use App\Models\Apprenant;
use App\Http\Resources\PromoResource;
use App\Models\PromoReferentielApprenant;


use App\Http\Resources\ReferentielResource;
use App\Http\Resources\PromoReferentielApprenantResource;
use App\Http\Resources\PromoReferentielApprenantCollection;
use App\Http\Requests\Promo_Referentiel_ApprenantStoreRequest;
use App\Http\Requests\Promo_Referentiel_ApprenantUpdateRequest;

class Promo_Referentiel_ApprenantController extends Controller
{
    public function index(Request $request): PromoReferentielApprenantCollection
    {
        $promoReferentielApprenants = PromoReferentielApprenant::all();

        return new PromoReferentielApprenantCollection($promoReferentielApprenants);
    }

    public function store(Promo_Referentiel_ApprenantStoreRequest $request): PromoReferentielApprenantResource
    {
        $promoReferentielApprenant = PromoReferentielApprenant::create($request->validated());

        return new PromoReferentielApprenantResource($promoReferentielApprenant);
    }

    public function show(Request $request, Promo_Referentiel_Apprenant $promoReferentielApprenant): PromoReferentielApprenantResource
    {
        return new PromoReferentielApprenantResource($promoReferentielApprenant);
    }


    public function update(Promo_Referentiel_ApprenantUpdateRequest $request, Promo_Referentiel_Apprenant $promoReferentielApprenant): PromoReferentielApprenantResource
    {
        $promoReferentielApprenant->update($request->validated());

        return new PromoReferentielApprenantResource($promoReferentielApprenant);
    }

    public function getApprenant(Request $request)
    {
        $promoReferentiel=PromoReferentiel::where([
            ['promo_id', '=',$request->promo_id],
            ['referentiel_id', '=', $request->referentiel_id]])->pluck('id');
            $promo = Promo::find($request->promo_id);
            $referentiel = Referentiel::find($request->referentiel_id);

        $promoReferentielApprenant= PromoReferentielApprenant::whereHas('apprenant', function ($query) {
            $query
            ->filter()
            ->whereIn('is_active', [1,0]);
        })->where(['promo_referentiel_id'=> $promoReferentiel])->get();
        $numActiveApprenants = Apprenant::join('promo_referentiel_apprenants', 'promo_referentiel_apprenants.apprenant_id', '=', 'apprenants.id')
        ->join('promo_referentiels', 'promo_referentiels.id', '=', 'promo_referentiel_apprenants.promo_referentiel_id')
        ->where('promo_referentiels.referentiel_id',$request->referentiel_id)
        ->where('apprenants.is_active', 1)
        ->count();
        $numInactiveApprenants = Apprenant::join('promo_referentiel_apprenants', 'promo_referentiel_apprenants.apprenant_id', '=', 'apprenants.id')
        ->join('promo_referentiels', 'promo_referentiels.id', '=', 'promo_referentiel_apprenants.promo_referentiel_id')
        ->where('promo_referentiels.referentiel_id',$request->referentiel_id)
        ->where('apprenants.is_active', 0)
        ->count();
        return [
            "promo"=> new PromoResource($promo),
            "referentiel"=> new ReferentielResource($referentiel),
            "apprenants"=>new PromoReferentielApprenantCollection($promoReferentielApprenant),
            "numActiveApprenants" =>$numActiveApprenants,
            "numInactiveApprenants" =>$numInactiveApprenants,

        ];
    }


    public function getApprenantNotActif(Request $request)
    {
        $promoReferentiel=PromoReferentiel::where([
            ['promo_id', '=',$request->promo_id],
            ['referentiel_id', '=', $request->referentiel_id]])->pluck('id');
            $promo = Promo::find($request->promo_id);
            $referentiel = Referentiel::find($request->referentiel_id);

        $promoReferentielApprenant= PromoReferentielApprenant::whereHas('apprenant', function ($query) {
            $query
            ->filter()
            ->whereIn('is_active', [0])->where('motif', '!=', null);
        })->where(['promo_referentiel_id'=> $promoReferentiel])
         ->get();
        $numActiveApprenants = Apprenant::join('promo_referentiel_apprenants', 'promo_referentiel_apprenants.apprenant_id', '=', 'apprenants.id')
        ->join('promo_referentiels', 'promo_referentiels.id', '=', 'promo_referentiel_apprenants.promo_referentiel_id')
        ->where('promo_referentiels.referentiel_id',$request->referentiel_id)
        ->where('apprenants.is_active', 1)
        ->count();
        $numInactiveApprenants = Apprenant::join('promo_referentiel_apprenants', 'promo_referentiel_apprenants.apprenant_id', '=', 'apprenants.id')
        ->join('promo_referentiels', 'promo_referentiels.id', '=', 'promo_referentiel_apprenants.promo_referentiel_id')
        ->where('promo_referentiels.referentiel_id',$request->referentiel_id)
        ->where('apprenants.is_active', 0)
        ->count();
        return [
            "promo"=> new PromoResource($promo),
            "referentiel"=> new ReferentielResource($referentiel),
            "numActiveApprenants" =>$numActiveApprenants,
            "numInactiveApprenants" =>$numInactiveApprenants,
            "apprenants"=>new PromoReferentielApprenantCollection($promoReferentielApprenant),
        ];
    }


    public function destroy(Request $request, Promo_Referentiel_Apprenant $promoReferentielApprenant): Response
    {
        $promoReferentielApprenant->delete();

        return response()->noContent();
    }

    
}
