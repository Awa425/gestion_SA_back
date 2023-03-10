<?php

namespace App\Http\Controllers;

use App\Models\Apprenant;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Resources\ApprenantResource;
use App\Models\PromoReferentielApprenant;
use App\Http\Resources\ApprenantCollection;
use App\Http\Requests\ApprenantStoreRequest;
use App\Http\Requests\ApprenantUpdateRequest;
use App\Http\Requests\import\ApprenantsImport;

class ApprenantController extends Controller
{
    public function index(Request $request)
    {
        if ((auth()->user()->cannot('manage') || auth()->user()->can('view')) && (auth()->user()->can('manage') || auth()->user()->cannot('view'))){
            return response([

                "message" => "vous n'avez pas le droit",

             ],401);
            }
            $apprenants = Apprenant::where('is_active', '=', '1')->get();            
            return new ApprenantCollection($apprenants);
       
    }

    public function store_in_table(ApprenantStoreRequest $request)
    {
        if ($request->user()->cannot('manage')){
            return response([
                "message" => "vous n'avez pas le droit",
             ],401);
         
          }
 
        $user_id=array(
            "user_id" =>auth()->user()->id
        );

        $apprenants=$request->validated();
        $apprenants['password']=bcrypt($apprenants['password']);
        $result = array_merge($apprenants,$user_id);
        $apprenant = Apprenant::create($result);

       
        $Promo_Referentiel_Apprenant=PromoReferentielApprenant::create([
              "promo_id" => $request->promo,
              "referentiel_id" => $request->referentiel,
              "apprenant_id" => $apprenant->id,
        ]);
        
        return new ApprenantResource($apprenant);
    }
    public function storeExcel(Request $request)
    {
        if ($request->user()->cannot('manage')){
            return response([
                "message" => "vous n'avez pas le droit",
             ],401);
         
          }
        if (!$request->hasFile('excel_file1')) {
            return response()->json([
                'message' => 'Veuillez sélectionner un fichier Excel à importer.'
            ], 422);
        }

        $file = $request->file('excel_file1');
        $promo_id=$request->promo;
        $referentiel_id=$request->referentiel;
        $data = Excel::import(new ApprenantsImport($promo_id, $referentiel_id), $file);
        return response()->json([
            'message' => 'Le fichier Excel a été importé avec succès.'
        ], 200);
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

          $apprenants=$request->validated();
          $apprenants['password']=bcrypt($apprenants['password']);
 
        $apprenant->update($apprenants);

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
