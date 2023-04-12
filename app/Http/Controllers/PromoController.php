<?php

namespace App\Http\Controllers;


use App\Models\Promo;
use App\Models\Referentiel;
use Illuminate\Http\Request;

use App\Models\PromoReferentiel;
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
        return new PromoResource($promo);

    }

    public function Referentiel(Request $request, $promo_id)
{

    $referentielsNotLinked = Referentiel::whereNotIn('id', function ($query) use ($promo_id){
        $query->select('referentiel_id')
            ->from('promo_referentiels')
            ->where('promo_id', $promo_id);
    })->get();
    return $referentielsNotLinked;
}
public function ReferentielLinked(Request $request, $promo_id)
{

    $referentielsLinked = Referentiel::whereIn('id', function ($query) use ($promo_id){
        $query->select('referentiel_id')
            ->from('promo_referentiels')
            ->where('promo_id', $promo_id);
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
    $referentielIds = $request->input('referentiels', []);

    // Attach the referentiels to the promo
    $promo->referentiels()->attach($referentielIds);

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
    $referentielIds = $request->input('referentiels', []);

    // Attach the referentiels to the promo
    $promo->referentiels()->detach($referentielIds);

    // Return the updated promo record
    return new PromoResource($promo);
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
