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
    public function index(Request $request)
    {
        if ((auth()->user()->cannot('manage') xor auth()->user()->cannot('view')) xor auth()->user()->cannot('vigil_job')){
            return response([

                "message" => "vous n'avez pas le droit",

             ],401);
            }
        $visiteurs = Visiteur::all();

        return new VisiteurCollection($visiteurs);
    }

    public function store(VisiteurStoreRequest $request)
    {
        if ((auth()->user()->cannot('manage') || auth()->user()->can('vigil_job')) && (auth()->user()->can('manage') || auth()->user()->cannot('vigil_job'))){
            return response([

                "message" => "vous n'avez pas le droit",

             ],401);
            }
        $user_id=array(
            "user_id" =>auth()->user()->id
        );
       
        $visiteurs=$request->validated();
        $result = array_merge($visiteurs,$user_id);
        $visiteur = Visiteur::create($result);

        return new VisiteurResource($visiteur);
    }

    public function show(Request $request, Visiteur $visiteur)
    {
        if ((auth()->user()->cannot('manage') xor auth()->user()->cannot('view')) xor auth()->user()->cannot('vigil_job')){
            return response([

                "message" => "vous n'avez pas le droit",

             ],401);
            }
        return new VisiteurResource($visiteur);
    }

    public function update(VisiteurUpdateRequest $request, Visiteur $visiteur)
    {
        if ((auth()->user()->cannot('manage') || auth()->user()->can('vigil_job')) && (auth()->user()->can('manage') || auth()->user()->cannot('vigil_job'))){
            return response([

                "message" => "vous n'avez pas le droit",

             ],401);
            }
        $visiteur->update($request->validated());

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
