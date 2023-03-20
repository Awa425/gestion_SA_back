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

        
       
          return new PromoReferentielApprenantCollection(PromoReferentielApprenant::whereHas('promo', function ($query) {
            $query
            ->filter()
            ->whereIn('is_active', [1]);
        })->paginate(request()->get('perpage', env('DEFAULT_PAGINATION')), ['*'], 'page')
           );
          
    }


    public function show(Promo $promo)
    {

       
        return new PromoResource($promo);
    }

    public function store(PromoStoreRequest $request)
    {


        $promos = $request->validatedAndFiltered();
        $promos['user_id'] = auth()->user()->id;

        $promos['date_fin_reel']= array_key_exists('date_fin_reel', $promos) ? $promos['date_fin_reel'] : $promos['date_fin_prevue'];
 
        $promo = Promo::create($promos);
       

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
            'is_active' => 0
        ]);

        return response()->noContent();
     
    }
}