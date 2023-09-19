<?php

namespace App\Http\Controllers;

use App\Http\Resources\PromoCollection;
use App\Models\Apprenant;
use App\Models\Promo;
use App\Models\Referentiel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // Get the total number of promos
    public function promos(Request $request){
        $numbPromos = Promo::all()->count();
        return $numbPromos;
    }


    // Get the total number of Apprenant
    public function apprenants(Request $request){
        $numbApprenants = Apprenant::all()->count();
        return $numbApprenants;
    }

      // Get the total number of Referenciel
      public function referenciel(Request $request){
        $numbRefs = Referentiel::all()->count();
        return $numbRefs;
    }

    // Get the total number of girl Apprenant
    public function numbApprenantFeminin(Request $request){
        $numbAppreFeminin = Apprenant::all()->where('genre', 'Feminin')->count();
        return $numbAppreFeminin;
    }

      // Get the total number of girl Apprenant
      public function numbApprenantGarcon(Request $request){
        $numbAppreMasculin = Apprenant::all()->where('genre', 'Masculin')->count();
        return $numbAppreMasculin;
    }

    public function apprenantActuel(){
        $apprenantActuel = Apprenant::join('promo_referentiel_apprenants', 'promo_referentiel_apprenants.apprenant_id', '=', 'apprenants.id');
        // return new PromoCollection(Promo::filter()->orderBy('created_at', 'desc')->first());
    }
}
