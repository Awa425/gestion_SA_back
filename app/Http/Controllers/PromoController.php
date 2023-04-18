<?php

namespace App\Http\Controllers;


use App\Models\Promo;
use App\Models\Referentiel;
use Illuminate\Http\Request;

use App\Models\PromoReferentiel;
use App\Models\PromoReferentielApprenant;
use App\Models\Apprenant;
use App\Http\Resources\PromoResource;

use App\Http\Resources\PromoCollection;
use App\Http\Requests\PromoStoreRequest;
use App\Http\Requests\PromoUpdateRequest;
use App\Http\Resources\PromoReferentielCollection;

class PromoController extends Controller
{
    public function index(Request $request)
    {


       return new PromoCollection(Promo::ignoreRequest(['perpage'])
       ->filter()
       ->where('is_active','=',1)
       ->paginate(env('DEFAULT_PAGINATION'), ['*'], 'page'));


    }




    public function show(Promo $promo)
    {


    $numActiveApprenants = Apprenant::join('promo_referentiel_apprenants', 'promo_referentiel_apprenants.apprenant_id', '=', 'apprenants.id')
    ->join('promo_referentiels', 'promo_referentiels.id', '=', 'promo_referentiel_apprenants.promo_referentiel_id')
    ->where('promo_referentiels.promo_id',$promo->id)
    ->where('apprenants.is_active', 1)
    ->count();
        return[
            "promo"=> new PromoResource($promo),
            "nombre_apprenant"=> $numActiveApprenants
        ];

    }


    public function Referentiel(Request $request, $promo_id)
{

    $referentielsNotLinked = Referentiel::whereNotIn('id', function ($query) use ($promo_id){
        $query->select('referentiel_id')
            ->from('promo_referentiels')
            ->where([
                ['promo_id', $promo_id],
                ['is_active', '=', 1],
            ]);
    })->get();
    return $referentielsNotLinked;
}
public function ReferentielLinked(Request $request, $promo_id)
{

    $referentielsLinked = Referentiel::whereIn('id', function ($query) use ($promo_id){
        $query->select('referentiel_id')
            ->from('promo_referentiels')
            ->where([
                ['promo_id', $promo_id],
                ['is_active', '=', 1],
            ]);
    })->get();
    return $referentielsLinked;
}


    public function addReferentiel(Request $request, $id)
{
    // Find the promo record by ID
    $promo = Promo::find($id);

    // Check if the promo record exists
    if ($promo === null) {
        return response()->json(['error' => 'Promo not found'], 404);
    }

    // Get the referentiel IDs from the request
    $referentielIds = $request->input('referentiels');

        $promoReferentiel = PromoReferentiel::where([
                ['referentiel_id', $referentielIds],
                ['promo_id', $promo->id],
            ])->first();
        if ($promoReferentiel !== null) {
            $promoReferentiel->update(['is_active' => 1]);
        }
        else{
            $promo->referentiels()->attach($referentielIds);
        }




    // Return the updated promo record
    return new PromoResource($promo);
}

public function removeReferentiel(Request $request, $id)
{
    // Find the promo record by ID
    $promo = Promo::find($id);

    // Check if the promo record exists
    if ($promo === null) {
        return response()->json(['error' => 'Promo not found'], 404);
    }

    // Get the referentiel IDs from the request
    $referentielIds = $request->input('referentiels');



        $promoReferentiel = PromoReferentiel::where([
                ['referentiel_id', $referentielIds],
                ['promo_id', $promo->id],
            ])->first();
            $apprenantsToUpdate = Apprenant::whereIn('id', function($query) use($referentielIds) {
                $query->select('apprenant_id')
                    ->from('promo_referentiel_apprenants')
                    ->join('promo_referentiels', 'promo_referentiel_apprenants.promo_referentiel_id', '=', 'promo_referentiels.id')
                    ->where('promo_referentiels.referentiel_id', '=', $referentielIds);
            })->first();

            if ($apprenantsToUpdate->count() > 0) {
                Apprenant::whereIn('id', function($query) use($referentielIds) {
                    $query->select('apprenant_id')
                        ->from('promo_referentiel_apprenants')
                        ->join('promo_referentiels', 'promo_referentiel_apprenants.promo_referentiel_id', '=', 'promo_referentiels.id')
                        ->where('promo_referentiels.referentiel_id', '=', $referentielIds);
                })->update(['is_active' => 0]);
            }
        if ($promoReferentiel !== null) {
            $promoReferentiel->update(['is_active' => 0]);
        }



    return response()->json(['message' => 'Désactiver avec succès'], 200);
}

    public function store(PromoStoreRequest $request,Referentiel ...$referentiels)
    {
        // if $referentiels is not provided, use an empty array
        $referentiels =$request->referentiels ?: [];

        $promos = $request->validatedAndFiltered();
        $promos['user_id'] = auth()->user()->id;
        $promos['date_fin_reel']= array_key_exists('date_fin_reel', $promos) ? $promos['date_fin_reel'] : $promos['date_fin_prevue'];


        $promo = Promo::create($promos);

        if (count($referentiels) >0) {

              $promo->referentiels()->attach($referentiels);

        }


        return new PromoResource($promo);

    }

    public function update(PromoUpdateRequest $request, Promo $promo,Referentiel ...$referentiels)
    {
        // if $referentiels is not provided, use an empty array
        $referentiels =$request->referentiels ?: [];
        $promo->update($request->validatedAndFiltered());
        if (count($referentiels) >0) {

            $promo->referentiels()->sync($referentiels);

      }
        return new PromoResource($promo);

    }

    public function destroy(Request $request, Promo $promo)
    {

        $promo->update([
            'is_active' => !$promo->is_active,

            'is_ongoing' => !$promo->is_ongoing,
        ]);

        return response()->json(['message' => 'Désactiver avec succès'], 200);



    }
}
