<?php

namespace App\Http\Controllers;

use App\Http\Resources\PromoCollection;
use App\Http\Resources\PromoReferentielApprenantCollection;
use App\Http\Resources\PromoResource;
use App\Http\Resources\ReferentielResource;
use App\Models\Apprenant;
use App\Models\Promo;
use App\Models\PromoReferentiel;
use App\Models\PromoReferentielApprenant;
use App\Models\Referentiel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // Get the total number of promos
    public function promos(Request $request){
        $numbPromos = Promo::get()->count();
        return $numbPromos;
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

    

    public function apprenantActuel()
    {
        $numActiveApprenants = Apprenant::join('promo_referentiel_apprenants', 'promo_referentiel_apprenants.apprenant_id', '=', 'apprenants.id')
        ->join('promo_referentiels', 'promo_referentiels.id', '=', 'promo_referentiel_apprenants.promo_referentiel_id')
        ->where('apprenants.is_active', 1)
        ->count();

        return $numActiveApprenants;
        
    }

    
}
