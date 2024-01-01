<?php

namespace App\Http\Controllers;

use App\Http\Resources\PromoCollection;
use App\Http\Resources\PromoInactivesCollection;
use App\Http\Resources\PromoReferentielApprenantCollection;
use App\Http\Resources\PromoReferentielApprenantResource;
use App\Http\Resources\PromoReferentielResource;
use App\Http\Resources\PromoResource;
use App\Http\Resources\ReferentielResource;
use App\Models\Apprenant;
use App\Models\Promo;
use App\Models\PromoReferentiel;
use App\Models\PromoReferentielApprenant;
use App\Models\Referentiel;
use eloquentFilter\QueryFilter\Queries\Where;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // Get the total number of promos
    public function promos(Request $request){
        $numbPromos = Promo::get()->count();
        return $numbPromos;
    }
    
    //Get all promos non active
    public function allPromoNonActiveAndApp(){
        return new PromoInactivesCollection(Promo::where('is_active',0)->get());
        // return response([
        //     'data'=>Promo::where('is_active',0)->get()
        // ]);
    }

    // Get the total number of Apprenant
    public function apprenants(Request $request){
        $numbApprenants = Apprenant::get()->count();
        return $numbApprenants;
    }

      // Get the total number of Referenciel
      public function referenciel(Request $request){
        $numbRefs = Referentiel::get()->count();
        return $numbRefs;
    }

    // Get the total number of girl Apprenant
    public function numbApprenantFeminin(Request $request){
        $numbAppreFeminin = Apprenant::where('genre', 'Feminin')->count();
        return $numbAppreFeminin;
    }

      // Get the total number of girl Apprenant
      public function numbApprenantGarcon(Request $request){
        $numbAppreMasculin = Apprenant::where('genre', 'Masculin')->count();
        return $numbAppreMasculin;
    }
    //  Get the total number of girl Apprenant By Promo
    public function getNumAppFemByPromoId($idPromo)
    {
        $idPromoRef= PromoReferentiel::where('promo_id', $idPromo)->pluck('id');

        return PromoReferentielApprenant::whereIn('promo_referentiel_id',$idPromoRef)
                ->join('apprenants','apprenants.id','=','promo_referentiel_apprenants.apprenant_id')
                ->where('genre','Feminin')
                ->get()
                ->count();
    }
    public function getNumAppMasByPromoId($idPromo)
    {
        $idPromoRef= PromoReferentiel::where('promo_id', $idPromo)->pluck('id');

        return PromoReferentielApprenant::whereIn('promo_referentiel_id',$idPromoRef)
                ->join('apprenants','apprenants.id','=','promo_referentiel_apprenants.apprenant_id')
                ->where('genre','Masculin')
                ->get()
                ->count();
    }
    
    public function getNumAppByPromo($idPromo){
        $idPromoRef= PromoReferentiel::where('promo_id', $idPromo)->pluck('id');
        return PromoReferentielApprenant::whereIn('promo_referentiel_id',$idPromoRef)
        ->join('apprenants','apprenants.id','=','promo_referentiel_apprenants.apprenant_id')
        ->get()
        ->count();
    }

    public function apprenantActuel()
    {
        $promoActuel=Promo::where('is_active',1)->first();
        if ($promoActuel) {
            $idPromoRef= PromoReferentiel::where('promo_id', $promoActuel->id)->pluck('id');
            return PromoReferentielApprenant::whereIn('promo_referentiel_id',$idPromoRef ? $idPromoRef : 0)
            ->join('apprenants','apprenants.id','=','promo_referentiel_apprenants.apprenant_id')
            ->get()
            ->count();
        }
    }
    public function getnbrAppByRef($idRef)
    {
        // $promoActuel=Promo::where('is_active',1)->first();
        $promoRef=PromoReferentiel::where(['referentiel_id'=>$idRef])->pluck('id');
        return response([
            'nbreApprenants'=>PromoReferentielApprenant::whereIn('promo_referentiel_id', $promoRef ? $promoRef : 0)
                                                        ->count()
        ]);
    }
}
