<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApprenantStoreRequest;
use App\Http\Requests\ApprenantUpdateRequest;
use App\Http\Resources\ApprenantCollection;
use App\Http\Resources\ApprenantResource;
use App\Models\Apprenant;
use App\Models\PromoReferentielApprenant;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\ApprenantIndexRequest;

class ApprenantController extends Controller
{
    public function index(Request $request)
    {
        if ((auth()->user()->cannot('manage') || auth()->user()->can('view')) && (auth()->user()->can('manage') || auth()->user()->cannot('view'))){
            return response([

                "message" => "vous n'avez pas le droit",

             ],401);
            }
    return new ApprenantCollection(Apprenant::ignoreRequest(['perpage'])
    ->filter()
    ->where('is_active', '=', '1')
    ->paginate(env('DEFAULT_PAGINATION'), ['*'], 'page'));
       
    }

    public function store_in_table(ApprenantStoreRequest $request)
    {
       
    
    if ($request->user()->cannot('manage')){
        return response([
            "message" => "vous n'avez pas le droit",
         ],401);
     }

     $data = $request->validatedAndFiltered();

     $data['password'] = bcrypt($data['password']);
     $data['user_id'] = auth()->user()->id;

     $apprenant = Apprenant::create($data);

    $promoReferentielApprenant = PromoReferentielApprenant::create([
        "promo_id" => $request->promo,
        "referentiel_id" => $request->referentiel,
        "apprenant_id" => $apprenant->id,
    ]);
        
        return new ApprenantResource($apprenant);
    }

    public function show(Request $request, Apprenant $apprenant)
    {
        if ((auth()->user()->cannot('manage') || auth()->user()->can('view')) && (auth()->user()->can('manage') || auth()->user()->cannot('view'))){
            return response([

                "message" => "vous n'avez pas le droit",

             ],401);
            }
       
        return new ApprenantResource($apprenant);
    }

    public function update(ApprenantUpdateRequest $request, Apprenant $apprenant)
    {
       
    
    if ($request->user()->cannot('manage')){
        return response([
            "message" => "vous n'avez pas le droit",
        ],401);
    }
   
      $validatedData = $request->validatedAndFiltered();

    if (isset($validatedData['password'])) {
        $validatedData['password'] = bcrypt($validatedData['password']);
    }

    $apprenant->update($validatedData);

    return new ApprenantResource($apprenant);
    }

    public function destroy(Request $request, Apprenant $apprenant): Response
    {
        if (auth()->user()->cannot('manage')){
            return response([
                "message" => "vous n'avez pas le droit",
             ],401);
         
          }
        $apprenant->update([
            'is_active' => 0
        ]);

        return response()->noContent();
    }
}
