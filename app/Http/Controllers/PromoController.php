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
use App\Http\Resources\PromoReferentielResource;

class PromoController extends Controller
{
    public function index(Request $request)
    {


       return new PromoCollection(Promo::ignoreRequest(['perpage'])
       ->filter()
       ->paginate(env('DEFAULT_PAGINATION'), ['*'], 'page'));


    }



    public function add_referentiel(Request $request,Referentiel ...$referentiels)
    {
        $referentiels = $referentiels ?: [];
        foreach ($referentiels as $referentiel) {
            PromoReferentiel::create([
                "promo_id" => $request['promo_id'],
                "referentiel_id" => $referentiel['id'],
            ]);
        }
        return new PromoReferentielCollection(PromoReferentiel::whereHas('promo', function ($query) {
            $query
            ->filter()
            ->whereIn('is_active', [1]);
        })->paginate(request()->get('perpage', env('DEFAULT_PAGINATION')), ['*'], 'page')
           );
    }
    public function show(Promo $promo)
    {
        //dd($promo->referentiels());
        
      
        return new PromoResource($promo);

    }

    public function store(PromoStoreRequest $request,Referentiel ...$referentiels)
    {
        // if $referentiels is not provided, use an empty array
        $referentiels = $referentiels ?: [];

        $promos = $request->validatedAndFiltered();
        $promos['user_id'] = auth()->user()->id;
        $promos['date_fin_reel']= array_key_exists('date_fin_reel', $promos) ? $promos['date_fin_reel'] : $promos['date_fin_prevue'];


        $promo = Promo::create($promos);

        if (count($referentiels) >0) {
            foreach ($referentiels as $referentiel) {
                PromoReferentiel::create([
                    "promo_id" => $promo['id'],
                    "referentiel_id" => $referentiel,
                ]);
            }
        }


        return new PromoResource($promo);

    }

    public function update(PromoUpdateRequest $request, Promo $promo)
    {

        $promo->update($request->validatedAndFiltered());

        return new PromoResource($promo);

    }

    public function destroy( Promo $promo)
    {

        $promo->update([
            'is_active' => 0,
            'is_ongoing' => 0,
        ]);

        return response()->noContent();

    }
}
