<?php

namespace App\Http\Controllers;

use App\Http\Requests\VisiteurStoreRequest;
use App\Http\Requests\VisiteurUpdateRequest;
use App\Http\Resources\VisiteurCollection;
use App\Http\Resources\VisiteurResource;
use App\Models\Visiteur;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VisiteurController extends Controller
{
    /**
      
     * @OA\Get(
     *    path="/api/visiteurs",
     *    operationId=" index ",
     *    tags={"visiteurs"},
     *    summary="Get list of visiteurs",
     *    description="Get list of visiteurs",
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



        return new VisiteurCollection(Visiteur::
        filter()
        ->get());
    }

    /**
     * @OA\Post(
     *      path="/api/visiteurs",
     *      operationId=" store ",
     *      tags={"visiteurs"},
     *      summary="Store visiteur in DB",
     *      description="Store visiteur in DB",
     *    security={{"bearerAuth":{}}}, 
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"nom", "content", "status"},
     *            @OA\Property(property="nom", type="string", format="string", example="nom"),
     *            @OA\Property(property="prenom", type="string", format="string", example="prenom"),
     *            @OA\Property(property="cni", type="string", format="string", example="cni"),
     *            @OA\Property(property="motif", type="string", format="string", example="motif"),
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

    public function store(VisiteurStoreRequest $request)
    {


        $data = $request->validatedAndFiltered();
        $data['user_id'] = auth()->user()->id;
        $visiteur = Visiteur::create($data);

        return new VisiteurResource($visiteur);
    }
    /**
     * @OA\Get(
     *    path="/api/visiteurs/{id}",
     *    operationId=" show ",
     *    tags={"visiteurs"},
     *    summary="Get visiteur Detail",
     *    security={{"bearerAuth":{}}}, 
     *    description="Get visiteur Detail",
     *    @OA\Parameter(name="id", in="path", description="Id of visiteur", required=true,
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

    public function show(Request $request, Visiteur $visiteur)
    {

        return new VisiteurResource($visiteur);
    }

      /**
     * @OA\Put(
     *     path="/api/visiteurs/{id}",
     *     operationId=" update ",
     *     tags={"visiteurs"},
     *     summary="Update visiteur in DB",
     *    security={{"bearerAuth":{}}}, 
     *     description="Update visiteur in DB",
     *     @OA\Parameter(name="id", in="path", description="Id of visiteur", required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *            required={"nom", "content", "status"},
     *            @OA\Property(property="nom", type="string", format="string", example="nom"),
     *            @OA\Property(property="prenom", type="string", format="string", example="prenom"),
     *            @OA\Property(property="cni", type="string", format="string", example="cni"),
     *            @OA\Property(property="motif", type="string", format="string", example="motif"),
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

    public function update(VisiteurUpdateRequest $request, Visiteur $visiteur)
    {

        $validatedData = $request->validatedAndFiltered();
        $visiteur->update($validatedData);

        return new VisiteurResource($visiteur);
    }
/*
    public function destroy(Request $request, Visiteur $visiteur): Response
    {
        $visiteur->delete();

        return response()->noContent();
    }
    */
}
