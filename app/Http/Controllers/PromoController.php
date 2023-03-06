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
        if ((auth()->user()->cannot('manage') || auth()->user()->can('view')) && (auth()->user()->can('manage') || auth()->user()->cannot('view'))){
            return response([

                "message" => "vous n'avez pas le droit",

             ],401);
         
          }
          
        $promos = Promo::where('is_active','=','1')->get();

        return new PromoCollection($promos);
          
    }


    public function show(Promo $promo)
    {
        return new PromoResource($promo);
    }

    public function store(PromoStoreRequest $request)
    {

        if ($request->user()->cannot('manage')){
           return response([
               "message" => "vous n'avez pas le droit",
            ],401);
        
         }

         
        
        $user_id=array(
            "user_id" =>auth()->user()->id
        );
        
        $promos=$request->validated();
        $result = array_merge($promos,$user_id);
        if($request->date_fin_reel)
        {
            $promo = Promo::create($result);
        }   
        else{                                                                                                               
        $date_fin_reel=array(
            "date_fin_reel" =>$request->date_fin_prevue
        );
        $result = array_merge($result,$date_fin_reel);
        $promo = Promo::create($result);

    }
        
       

        return new PromoResource($promo);
           
    }

    public function update(PromoUpdateRequest $request, Promo $promo)
    {
        if ($request->user()->cannot('manage')){
            return response([
                "message" => "vous n'avez pas le droit",
             ],401);
         
          }
          
          
        
        $promo->update($request->validated());

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