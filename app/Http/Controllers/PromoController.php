<?php

namespace App\Http\Controllers;

use App\Http\Requests\PromoStoreRequest;
use App\Http\Requests\PromoUpdateRequest;
use App\Http\Resources\PromoCollection;
use App\Http\Resources\PromoResource;
use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index()
    {
        $promos = Promo::where('is_active','=','1')->get();

        return new PromoCollection($promos);
    }


    public function show(Promo $promo)
    {
        return new PromoResource($promo);
    }

    public function store(PromoStoreRequest $request)
    {
        
        $user_id=auth()->user()->id;
        
        $promo = Promo::create([
            'libelle' => $request->libelle,
            'annee' => $request->annee,
            'user_id' => $user_id,
    
        ]);
       

        return new PromoResource($promo);
    }

    public function update(PromoUpdateRequest $request, Promo $promo)
    {
        
        $promo->update($request->validated());

        return new PromoResource($promo);
    }

    public function destroy( Promo $promo)
    {
        $promo->update([
            'is_active' => 0
        ]);

        return response()->noContent();
    }
}
