<?php

namespace App\Http\Controllers;

use App\Http\Requests\PromoStoreRequest;
use App\Http\Requests\PromoUpdateRequest;
use App\Http\Resources\PromoCollection;
use App\Http\Resources\PromoResource;
use App\Models\Promo;
use App\Models\User;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index()
    {
        if ((auth()->user()->cannot('manage_promo') || auth()->user()->can('view_promo')) && (auth()->user()->can('manage_promo') || auth()->user()->cannot('view_promo'))){
            return response([
                "message" => "vous n'avez pas le droit",
             ]);
         
          }
          else{
        $promos = Promo::where('is_active','=','1')->get();

        return new PromoCollection($promos);
          }
    }


    public function show(Promo $promo)
    {
        return new PromoResource($promo);
    }

    public function store(PromoStoreRequest $request)
    {

        if ($request->user()->cannot('manage_promo')){
           return response([
               "message" => "vous n'avez pas le droit",
            ]);
        
         }

         else{
        
        $user_id=auth()->user()->id;
        
        $promo = Promo::create([
            'libelle' => $request->libelle,
            'annee' => $request->annee,
            'user_id' => $user_id,
    
        ]);
       

        return new PromoResource($promo);
           }
    }

    public function update(PromoUpdateRequest $request, Promo $promo)
    {
        if ($request->user()->cannot('manage_promo')){
            return response([
                "message" => "vous n'avez pas le droit",
             ]);
         
          }
          else{
        
        $promo->update($request->validated());

        return new PromoResource($promo);
          }
    }

    public function destroy( Promo $promo)
    {
        if ($request->user()->cannot('manage_promo')){
            return response([
                "message" => "vous n'avez pas le droit",
             ]);
         
          }
          else{
        $promo->update([
            'is_active' => 0
        ]);

        return response()->noContent();
     }
    }
}
