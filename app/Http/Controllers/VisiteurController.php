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
        
       

        return new VisiteurCollection(Visiteur::ignoreRequest(['perpage'])
        ->filter()
        ->paginate(env('DEFAULT_PAGINATION'), ['*'], 'page'));
    }

    public function store(VisiteurStoreRequest $request)
    {
        
       
        $data = $request->validatedAndFiltered();
        $data['user_id'] = auth()->user()->id;
        $visiteur = Visiteur::create($data);

        return new VisiteurResource($visiteur);
    }

    public function show(Request $request, Visiteur $visiteur)
    {
        
        return new VisiteurResource($visiteur);
    }

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
