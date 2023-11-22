<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use  App\Models\User ;
use App\Models\Referentiel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\ReferentielResource;
use App\Http\Resources\ReferentielCollection;
use App\Http\Requests\ReferentielStoreRequest;
use App\Http\Requests\ReferentielUpdateRequest;


class ReferentielController extends Controller
{
     /**
      
     * @OA\Get(
     *    path="/api/referentiels",
     *    operationId="  index  ",
     *    tags={"referentiels"},
     *    summary="Get list of referentiels",
     *    description="Get list of referentiels",
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
   
    public function index()
    {
        return new ReferentielCollection(Referentiel::ignoreRequest(['perpage'])
        ->filter()
        // ->where('is_active', "=", 1)
        ->orderByDesc('is_active')
        ->paginate(request()
            ->get('perpage', env('DEFAULT_PAGINATION')), ['*'], 'page')
         );
    }

    /**
     * @OA\Post(
     *      path="/api/referentiels",
     *      operationId="  store  ",
     *      tags={"referentiels"},
     *      summary="Store referentiel in DB",
     *      description="Store referentiel in DB",
     *    security={{"bearerAuth":{}}}, 
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"libelle", "content", "status"},
     *            @OA\Property(property="libelle", type="string", format="string", example="libelle"),
     *            @OA\Property(property="description", type="string", format="string", example="description"),
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

    public function store(ReferentielStoreRequest $request)
    {
        $validatedData = $request->validated();
        $u= array('user_id' => auth()->user()->id);
        $referentiel = Referentiel::create(array_merge($validatedData,$u));
        return new ReferentielResource($referentiel);


    }

    /**
     * @OA\Get(
     *    path="/api/referentiels/{id}",
     *    operationId="  show  ",
     *    tags={"referentiels"},
     *    summary="Get referentiel Detail",
     *    security={{"bearerAuth":{}}}, 
     *    description="Get referentiel Detail",
     *    @OA\Parameter(name="id", in="path", description="Id of referentiel", required=true,
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

    public function show(Request $request, Referentiel $referentiel)
    {
        return new ReferentielResource($referentiel);
    }
/**
     * @OA\Put(
     *     path="/api/referentiels/{id}",
     *     operationId="  update  ",
     *     tags={"referentiels"},
     *     summary="Update referentiel in DB",
     *    security={{"bearerAuth":{}}}, 
     *     description="Update referentiel in DB",
     *     @OA\Parameter(name="id", in="path", description="Id of referentiel", required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *          required={"libelle", "content", "status"},
     *            @OA\Property(property="libelle", type="string", format="string", example="libelle"),
     *            @OA\Property(property="description", type="string", format="string", example="description"),
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
    public function update(ReferentielUpdateRequest $request, Referentiel $referentiel)
    {

        $referentiel->update($request->validated());

        return new ReferentielResource($referentiel);
    }
    /**
         * @OA\Delete(
         *    path="/api/referentiels/{id}",
         *    operationId="  destroy  ",
         *    tags={"referentiels"},
         *    summary="Delete referentiel",
         *    security={{"bearerAuth":{}}}, 
         *    description="Delete referentiel",
         *    @OA\Parameter(name="id", in="path", description="Id of referentiel", required=true,
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

    public function destroy(Request $request, Referentiel $referentiel)
    {
         $referentiel->update([
            'is_active' => !$referentiel->is_active,
        ]);
    
        return response()->json(['message' => 'Désactiver avec succès'], 200);
    }
    
}
