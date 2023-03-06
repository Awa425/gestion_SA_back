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
    public function index(Request $request): VisiteurCollection
    {
        $visiteurs = Visiteur::all();

        return new VisiteurCollection($visiteurs);
    }

    public function store(VisiteurStoreRequest $request): VisiteurResource
    {
        $visiteur = Visiteur::create($request->validated());

        return new VisiteurResource($visiteur);
    }

    public function show(Request $request, Visiteur $visiteur): VisiteurResource
    {
        return new VisiteurResource($visiteur);
    }

    public function update(VisiteurUpdateRequest $request, Visiteur $visiteur): VisiteurResource
    {
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
