<?php

namespace App\Http\Controllers;


use App\Models\Promo;
use App\Models\Referentiel;
use Illuminate\Http\Request;

use App\Models\PromoReferentiel;
use App\Models\PromoReferentielApprenant;
use App\Models\Apprenant;
use App\Http\Resources\PromoResource;

use App\Http\Resources\PromoCollection;
use App\Http\Requests\PromoStoreRequest;
use App\Http\Requests\PromoUpdateRequest;
use App\Http\Resources\PromoReferentielCollection;
use App\Http\Resources\PromoReferentielResource;

class PromoController extends Controller
{
       /**
      
     * @OA\Get(
     *    path="/api/promos",
     *    operationId=" index",
     *    tags={"promos"},
     *    summary="Get list of promos",
     *    description="Get list of promos",
     *    security={{"bearerAuth":{}}}, 
     *    @OA\Parameter(name="limit", in="query", description="limit", required=false,
     *        @OA\Schema(type="integer")
     *    ),
     *    @OA\Parameter(name="page", in="query", description="the page number", required=false,
     *        @OA\Schema(type="integer")
     *    ),
     *    @OA\Parameter(name="order", in="query", description="order  accepts 'asc' or 'desc'", required=false,
     *        @OA\Schema(type="string")
     *    ),
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example="200"),
     *             @OA\Property(property="data",type="object")
     *          )
     *       )
     *  )
     */
    public function index(Request $request)
    {


        return new PromoCollection(Promo::filter()->orderBy('created_at', 'desc')->get());


    }
//    get promo actuelle
    public function getPromoActuel(){
        $promoActuel= Promo::where("is_active",1)->first();
        return $promoActuel->id;
    }


/**
     * @OA\Post(
     *      path="/api/promos",
     *      operationId=" store",
     *      tags={"promos"},
     *      summary="Store promo in DB",
     *      description="Store promo in DB",
     *    security={{"bearerAuth":{}}}, 
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"libelle", "content", "status"},
     *            @OA\Property(property="libelle", type="string", format="string", example="libelle"),
     *            @OA\Property(property="date_debut", type="string", format="date", example="date_debut"),
     *            @OA\Property(property="date_fin_prevue", type="string", format="date", example="date_fin_prevue"),
     *         ),
     *      ),
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=""),
     *             @OA\Property(property="data",type="object")
     *          )
     *       )
     *  )
     */

    public function show(Promo $promo)
    {


    $numActiveApprenants = Apprenant::join('promo_referentiel_apprenants', 'promo_referentiel_apprenants.apprenant_id', '=', 'apprenants.id')
    ->join('promo_referentiels', 'promo_referentiels.id', '=', 'promo_referentiel_apprenants.promo_referentiel_id')
    ->where('promo_referentiels.promo_id',$promo->id)
    ->where('apprenants.is_active', 1)
    ->count();
    $numInActiveApprenants = Apprenant::join('promo_referentiel_apprenants', 'promo_referentiel_apprenants.apprenant_id', '=', 'apprenants.id')
    ->join('promo_referentiels', 'promo_referentiels.id', '=', 'promo_referentiel_apprenants.promo_referentiel_id')
    ->where('promo_referentiels.promo_id',$promo->id)
    ->where('apprenants.is_active', 0)
    ->count();
    $promoReferentiel=PromoReferentiel::where(['promo_id'=>$promo->id,'is_active'=>0])->get('referentiel_id');
        return[
            "promo"=> new PromoResource($promo),
            "nombre_apprenant"=> $numActiveApprenants,
            "nombre_apprenant_inactive"=> $numInActiveApprenants,
            "refI"=>$promoReferentiel, 
        ];
    
    }
 /**
     * @OA\Get(
     *    path="/api/promos/{id}",
     *    operationId=" show",
     *    tags={"promos"},
     *    summary="Get promo Detail",
     *    security={{"bearerAuth":{}}}, 
     *    description="Get promo Detail",
     *    @OA\Parameter(name="id", in="path", description="Id of promo", required=true,
     *        @OA\Schema(type="integer")
     *    ),
     *     @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *          @OA\Property(property="status_code", type="integer", example="200"),
     *          @OA\Property(property="data",type="object")
     *           ),
     *        )
     *       )
     *  )
     */

    public function Referentiel(Request $request, $promo_id)
{

    $referentielsNotLinked = Referentiel::whereNotIn('id', function ($query) use ($promo_id){
        $query->select('referentiel_id')
            ->from('promo_referentiels')
            ->where([
                ['promo_id', $promo_id],
                ['is_active', '=', 1],
            ]);
    })->get();
    return $referentielsNotLinked;
}
public function ReferentielLinked(Request $request, $promo_id)
{

    $referentielsLinked = Referentiel::whereIn('id', function ($query) use ($promo_id){
        $query->select('referentiel_id')
            ->from('promo_referentiels')
            ->where([
                ['promo_id', $promo_id],
                ['is_active', '=', 1],
            ]);
    })->get();
    return $referentielsLinked;
}


    public function addReferentiel(Request $request, $id)
{
    // Find the promo record by ID
    $promo = Promo::find($id);

    // Check if the promo record exists
    if ($promo === null) {
        return response()->json(['error' => 'Promo not found'], 404);
    }

    // Get the referentiel IDs from the request
    $referentielIds = $request->input('referentiels');

        $promoReferentiel = PromoReferentiel::where([
                ['referentiel_id', $referentielIds],
                ['promo_id', $promo->id],
            ])->first();
        if ($promoReferentiel !== null) {
            $apprenantsToUpdate = Apprenant::whereIn('id', function($query) use($referentielIds, $promo) {
                $query->select('apprenant_id')
                    ->from('promo_referentiel_apprenants')
                    ->join('promo_referentiels', 'promo_referentiel_apprenants.promo_referentiel_id', '=', 'promo_referentiels.id')
                    ->where('promo_referentiels.referentiel_id', '=', $referentielIds)
                    ->where('promo_referentiels.promo_id', '=', $promo->id);
            })->first();

            if ($apprenantsToUpdate) {
                Apprenant::whereIn('id', function($query) use($referentielIds, $promo) {
                    $query->select('apprenant_id')
                        ->from('promo_referentiel_apprenants')
                        ->join('promo_referentiels', 'promo_referentiel_apprenants.promo_referentiel_id', '=', 'promo_referentiels.id')
                        ->where('promo_referentiels.referentiel_id', '=', $referentielIds)
                        ->where('promo_referentiels.promo_id', '=', $promo->id);
                })->update(['is_active' => 1]);
                
            }
            $promoReferentiel->update(['is_active' => 1]);
        }
        else{
            $promo->referentiels()->attach($referentielIds);
        }




    // Return the updated promo record
    return new PromoResource($promo);
}

public function removeReferentiel(Request $request, $id)
{
    // Find the promo record by ID
    $promo = Promo::find($id);

    // Check if the promo record exists
    if ($promo === null) {
        return response()->json(['error' => 'Promo not found'], 404);
    }

    // Get the referentiel IDs from the request
    $referentielIds = $request->input('referentiels');



        $promoReferentiel = PromoReferentiel::where([
                ['referentiel_id', $referentielIds],
                ['promo_id', $promo->id],
            ])->first();
            $apprenantsToUpdate = Apprenant::whereIn('id', function($query) use($referentielIds, $promo) {
                $query->select('apprenant_id')
                    ->from('promo_referentiel_apprenants')
                    ->join('promo_referentiels', 'promo_referentiel_apprenants.promo_referentiel_id', '=', 'promo_referentiels.id')
                    ->where('promo_referentiels.referentiel_id', '=', $referentielIds)
                    ->where('promo_referentiels.promo_id', '=', $promo->id);
            })->first();

            if ($apprenantsToUpdate) {
                Apprenant::whereIn('id', function($query) use($referentielIds, $promo) {
                    $query->select('apprenant_id')
                        ->from('promo_referentiel_apprenants')
                        ->join('promo_referentiels', 'promo_referentiel_apprenants.promo_referentiel_id', '=', 'promo_referentiels.id')
                        ->where('promo_referentiels.referentiel_id', '=', $referentielIds)
                        ->where('promo_referentiels.promo_id', '=', $promo->id);
                })->update(['is_active' => 0]);
            }
        if ($promoReferentiel !== null) {
            $promoReferentiel->update(['is_active' => 0]);
        }



    return response()->json(['message' => 'Désactiver avec succès'], 200);
}

    public function store(PromoStoreRequest $request,Referentiel ...$referentiels)
    {
        // if $referentiels is not provided, use an empty array
        $referentiels =$request->referentiels ?: [];

        $promos = $request->validatedAndFiltered();
        $promos['user_id'] = auth()->user()->id;
        $promos['date_fin_reel']= array_key_exists('date_fin_reel', $promos) ? $promos['date_fin_reel'] : $promos['date_fin_prevue'];


        $promo = Promo::create($promos);

        if (count($referentiels) >0) {

              $promo->referentiels()->attach($referentiels);

        }
        //disable last promo
        $previouspromo=Promo::where('id', '<>', $promo->id)
        ->orderBy('created_at', 'desc')
        ->limit(1)
        ->first();
        if($previouspromo){
            $referentielIds=PromoReferentiel::where('promo_id',$previouspromo->id)->pluck('referentiel_id');
                if( $referentielIds){
                    foreach($referentielIds as $referentielId){
                        $apprenantsToUpdate = Apprenant::whereIn('id', function($query) use($referentielId, $previouspromo) {
                            $query->select('apprenant_id')
                                ->from('promo_referentiel_apprenants')
                                ->join('promo_referentiels', 'promo_referentiel_apprenants.promo_referentiel_id', '=', 'promo_referentiels.id')
                                ->where('promo_referentiels.referentiel_id', '=', $referentielId)
                                ->where('promo_referentiels.promo_id', '=', $previouspromo->id);
                        })->first();
            
                        if ($apprenantsToUpdate) {
                            Apprenant::whereIn('id', function($query) use($referentielId, $previouspromo) {
                                $query->select('apprenant_id')
                                    ->from('promo_referentiel_apprenants')
                                    ->join('promo_referentiels', 'promo_referentiel_apprenants.promo_referentiel_id', '=', 'promo_referentiels.id')
                                    ->where('promo_referentiels.referentiel_id', '=', $referentielId)
                                    ->where('promo_referentiels.promo_id', '=', $previouspromo->id);
                            })->update(['is_active' => 0]);
                        }
                     }
                    }
    
    
           $promoReferentiels=PromoReferentiel::where('promo_id',$previouspromo->id)->update(
                            ['is_active' => 0]);
            $previouspromo->update([
                'is_active' => !$previouspromo->is_active,
    
                'is_ongoing' => !$previouspromo->is_ongoing,
            ]);
    
        }
        return new PromoResource($promo);

    }

      /**
     * @OA\Put(
     *     path="/api/promos/{id}",
     *     operationId=" update",
     *     tags={"promos"},
     *     summary="Update promo in DB",
     *    security={{"bearerAuth":{}}}, 
     *     description="Update promo in DB",
     *     @OA\Parameter(name="id", in="path", description="Id of promo", required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *           required={"libelle", "content", "status"},
     *             @OA\Property(property="libelle", type="string", format="string", example="libelle"),
     *            @OA\Property(property="date_debut", type="string", format="date", example="date_debut"),
     *            @OA\Property(property="date_fin_prevue", type="string", format="date", example="date_fin_prevue"),
     *         ),
     *     ),
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example="200"),
     *             @OA\Property(property="data",type="object")
     *          )
     *       )
     *  )
     */

    public function update(PromoUpdateRequest $request, Promo $promo,Referentiel ...$referentiels)
    {
        // if $referentiels is not provided, use an empty array
        $referentiels =$request->referentiels ?: [];
        $promo->update($request->validatedAndFiltered());
        if (count($referentiels) >0) {

            $promo->referentiels()->sync($referentiels);

      }
        return new PromoResource($promo);

    }

     /**
     * @OA\Delete(
     *    path="/api/promos/{id}",
     *    operationId=" destroy",
     *    tags={"promos"},
     *    summary="Delete promo",
     *    security={{"bearerAuth":{}}}, 
     *    description="Delete promo",
     *    @OA\Parameter(name="id", in="path", description="Id of promo", required=true,
     *        @OA\Schema(type="integer")
     *    ),
     *    @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *         @OA\Property(property="status_code", type="integer", example="200"),
     *         @OA\Property(property="data",type="object")
     *          ),
     *       )
     *      )
     *  )
     */

    public function destroy(Request $request, Promo $promo)
    {
         if($promo->is_active==true){
        $referentielIds=PromoReferentiel::where('promo_id',$promo->id)->pluck('referentiel_id');
            if( $referentielIds){
                foreach($referentielIds as $referentielId){
                    $apprenantsToUpdate = Apprenant::whereIn('id', function($query) use($referentielId, $promo) {
                        $query->select('apprenant_id')
                            ->from('promo_referentiel_apprenants')
                            ->join('promo_referentiels', 'promo_referentiel_apprenants.promo_referentiel_id', '=', 'promo_referentiels.id')
                            ->where('promo_referentiels.referentiel_id', '=', $referentielId)
                            ->where('promo_referentiels.promo_id', '=', $promo->id);
                    })->first();
        
                    if ($apprenantsToUpdate) {
                        Apprenant::whereIn('id', function($query) use($referentielId, $promo) {
                            $query->select('apprenant_id')
                                ->from('promo_referentiel_apprenants')
                                ->join('promo_referentiels', 'promo_referentiel_apprenants.promo_referentiel_id', '=', 'promo_referentiels.id')
                                ->where('promo_referentiels.referentiel_id', '=', $referentielId)
                                ->where('promo_referentiels.promo_id', '=', $promo->id);
                        })->update(['is_active' => 0]);
                    }
                 }
                }


       $promoReferentiels=PromoReferentiel::where('promo_id',$promo->id)->update(
                        ['is_active' => 0]);
        $promo->update([
            'is_active' => !$promo->is_active,

            'is_ongoing' => !$promo->is_ongoing,
        ]);

    }
    else{
        $referentielIds=PromoReferentiel::where('promo_id',$promo->id)->pluck('referentiel_id');
            if( $referentielIds){
                foreach($referentielIds as $referentielId){
                    $apprenantsToUpdate = Apprenant::whereIn('id', function($query) use($referentielId, $promo) {
                        $query->select('apprenant_id')
                            ->from('promo_referentiel_apprenants')
                            ->join('promo_referentiels', 'promo_referentiel_apprenants.promo_referentiel_id', '=', 'promo_referentiels.id')
                            ->where('promo_referentiels.referentiel_id', '=', $referentielId)
                            ->where('promo_referentiels.promo_id', '=', $promo->id);
                    })->first();
        
                    if ($apprenantsToUpdate) {
                        Apprenant::whereIn('id', function($query) use($referentielId, $promo) {
                            $query->select('apprenant_id')
                                ->from('promo_referentiel_apprenants')
                                ->join('promo_referentiels', 'promo_referentiel_apprenants.promo_referentiel_id', '=', 'promo_referentiels.id')
                                ->where('promo_referentiels.referentiel_id', '=', $referentielId)
                                ->where('promo_referentiels.promo_id', '=', $promo->id);
                        })->update(['is_active' => 1]);
                    }
                 }
                }


       $promoReferentiels=PromoReferentiel::where('promo_id',$promo->id)->update(
                        ['is_active' => 1]);
        $promo->update([
            'is_active' => !$promo->is_active,

            'is_ongoing' => !$promo->is_ongoing,
        ]);
    }

        return response()->json(['message' => 'Désactiver avec succès'], 200);



    }
}
