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

class PromoController extends Controller
{
    public function index(PromoIndexRequest $request)
    {
        if ((auth()->user()->cannot('manage') || auth()->user()->can('view')) && (auth()->user()->can('manage') || auth()->user()->cannot('view'))){
            return response([

                "message" => "vous n'avez pas le droit",

             ],401);
         
          }
          
        $query = Promo::where('is_active','=','1');
        // Apply filters
    if ($request->has('libelle')) {
        $query->where('libelle', 'like', '%'.$request->input('libelle').'%');
    }

   
    if ($request->has('date_debut')) {
        $query->where('date_debut', '=', $request->input('date_debut'));
    }
    if ($request->has('date_fin_prevue')) {
        $query->where('date_fin_prevue', '=', $request->input('date_fin_prevue'));
    }
    if ($request->has('date_fin_reel')) {
        $query->where('date_fin_reel', '=', $request->input('date_fin_reel'));
    }  

   
    // Apply sorting
    if ($request->has('sort')) {
        $sortField = $request->input('sort');
        $sortDirection = $request->input('direction', 'asc');
        $query->orderBy($sortField, $sortDirection);
    }

     // Get paginated results
     $perPage = $request->input('per_page', env('DEFAULT_PAGINATION', 15));
     $promos = $query->paginate($perPage);
       
    
        return new PromoCollection($promos);
          
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