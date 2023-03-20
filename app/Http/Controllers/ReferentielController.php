<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReferentielStoreRequest;
use App\Http\Requests\ReferentielUpdateRequest;
use App\Http\Resources\ReferentielCollection;
use App\Http\Resources\ReferentielResource;
use App\Models\Referentiel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use  App\Models\User ;


class ReferentielController extends Controller
{

    public function index(Request $request)
    {
       
        $perPage = $request->input('per_page', env('DEFAULT_PAGINATION', 10)); 
        $referentiels = Referentiel::where('is_active', true)->paginate($perPage);
        return new ReferentielCollection($referentiels);
    }

    // // cette fonction permet de recuperer les promos d'un referentiel
    // public function promosRef($id)
    // {

    //     $referentiel = Referentiel::findOrFail($id);
    //     $promos = $referentiel->promos;
    //     return response()->json($promos);
    // }

    public function store(ReferentielStoreRequest $request)
    {
        $validatedData = $request->validated();
        $u= array('userid' => auth()->user()->id);
        $referentiel = Referentiel::create(array_merge($validatedData,$u));
        return new ReferentielResource($referentiel);


    }

    public function show(Request $request, Referentiel $referentiel)
    {
        return new ReferentielResource($referentiel);
    }

    public function update(ReferentielUpdateRequest $request, Referentiel $referentiel): ReferentielResource
    {
        
        $referentiel->update($request->validated());

        return new ReferentielResource($referentiel);
    }

    public function destroy(Request $request, Referentiel $referentiel): Response
    {
       $res= $referentiel->update([
            'is_active' => false,
        ]);

        return response()->noContent();
    }
}
