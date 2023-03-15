<?php

namespace App\Http\Controllers;

use App\Http\Requests\PromoStoreRequest;
use App\Http\Requests\PromoUpdateRequest;
use App\Http\Requests\PromoIndexRequest;
use App\Http\Resources\PromoCollection;
use App\Http\Resources\PromoResource;
use App\Models\Promo;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\PromoReferentielApprenantCollection;
use App\Http\Resources\PromoReferentielApprenantResource;
use App\Models\PromoReferentielApprenant;


class PromoController extends Controller
{
    public function index(Request $request)
    {
        if ((auth()->user()->cannot('manage') || auth()->user()->can('view')) && (auth()->user()->can('manage') || auth()->user()->cannot('view'))){
            return response([

                "message" => "vous n'avez pas le droit",

             ],401);
         
          }
          
       
          return new PromoReferentielApprenantCollection(PromoReferentielApprenant::whereHas('promo', function ($query) {
            $query
            ->filter()
            ->whereIn('is_active', [1]);
        })->paginate(request()->get('perpage', env('DEFAULT_PAGINATION')), ['*'], 'page')
           );
          
    }


    public function show(Promo $promo)
    {
        if ((auth()->user()->cannot('manage') || auth()->user()->can('view')) && (auth()->user()->can('manage') || auth()->user()->cannot('view'))){
            return response([

                "message" => "vous n'avez pas le droit",

             ],401);
            }
       
        return new PromoResource($promo);
    }

    public function store(PromoStoreRequest $request)
    {

        if ($request->user()->cannot('manage')){
           return response([
               "message" => "vous n'avez pas le droit",
            ],401);
        
         }

        $promos = $request->validatedAndFiltered();
        $promos['user_id'] = auth()->user()->id;

        $promos['date_fin_reel']= array_key_exists('date_fin_reel', $promos) ? $promos['date_fin_reel'] : $promos['date_fin_prevue'];
 
        $promo = Promo::create($promos);
       

        return new PromoResource($promo);
           
    }

    public function update(PromoUpdateRequest $request, Promo $promo)
    {
        if ($request->user()->cannot('manage')){
            return response([
                "message" => "vous n'avez pas le droit",
             ],401);
         
          }
          
          
        
        $promo->update($request->validatedAndFiltered());

        return new PromoResource($promo);
          
    }

    public function destroy( Promo $promo)
    {
        if (auth()->user()->cannot('manage')){
            return response([
                "message" => "vous n'avez pas le droit",
             ],401);
         
          }
         
        $promo->update([
            'is_active' => 0
        ]);

        return response()->noContent();
     
    }
}