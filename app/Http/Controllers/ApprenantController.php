<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApprenantStoreRequest;
use App\Http\Requests\ApprenantUpdateRequest;
use App\Http\Resources\ApprenantCollection;
use App\Http\Resources\ApprenantResource;
use App\Models\Apprenant;
use App\Models\Referentiel;
use App\Models\Promo;
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


    public function generate_matricule($promo_id,$referentiel_id){

            $promo = Promo::where('id','=',$promo_id)->select('libelle')->first();
            $referentiel = Referentiel::where('id','=',$referentiel_id)->select('libelle')->first();


            $promo_tabs = explode(' ', $promo['libelle']);
            $referentiel_tabs=explode(' ', $referentiel['libelle']);
            $promo_prefix = '';
            $referentiel_prefix = '';

            foreach ($promo_tabs as $promo_tab) {
            $promo_prefix .= strtoupper(substr($promo_tab, 0, 1)) ;
            
            }
            foreach ($referentiel_tabs as $referentiel_tab) {
            $referentiel_prefix.= strtoupper(substr($referentiel_tab, 0, 1)) ;
            
            }


            $date = date('YmdHis'). substr(microtime(), 2, 3);
            $matricule= $promo_prefix.'_'.$referentiel_prefix.'_'. $date;
            return $matricule;
    }

    public function store(ApprenantStoreRequest $request)
    {
       
    
    if ($request->user()->cannot('manage')){
        return response([
            "message" => "vous n'avez pas le droit",
         ],401);
     }

     $data = $request->validatedAndFiltered();

     $data['password'] = bcrypt($data['password']);
     $data['user_id'] = auth()->user()->id;
    
     $data['matricule']= $this->generate_matricule($request->promo_id,$request->referentiel_id);
     
     
     return $data['matricule'];

     //insert into apprenant
     
     //$apprenant = Apprenant::create($data);

     //insert into promoReferentielApprenant
    $promoReferentielApprenant = PromoReferentielApprenant::create([
        "promo_id" => $request->promo_id,
        "referentiel_id" => $request->referentiel_id,
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
