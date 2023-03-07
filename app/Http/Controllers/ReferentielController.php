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
        if ((auth()->user()->cannot('manage') || auth()->user()->can('view')) && (auth()->user()->can('manage') || auth()->user()->cannot('view'))){
            abort(403, 'Unauthorized action.');
         
        }
       $referentiels = Referentiel::where('is_active', true)->get();

        return new ReferentielCollection($referentiels);
    }
    public function promosRef($id)
    {
        if (auth()->user()->cannot('manage')) {
            abort(403, 'Unauthorized action.');
        }

        $referentiel = Referentiel::findOrFail($id);
        $promos = $referentiel->promos;
        return response()->json($promos);
    }

    public function store(ReferentielStoreRequest $request)
    {
        if (auth()->user()->cannot('manage')){
            abort(403, 'Unauthorized action.');
         
        }
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
        
        if (auth()->user()->cannot('manage')){
            abort(403, 'Unauthorized action.');
         
        }
        $referentiel->update($request->validated());

        return new ReferentielResource($referentiel);
    }

    public function destroy(Request $request, Referentiel $referentiel): Response
    {
        $user = auth()->user();
        if(auth()->user()->cannot('manage')){
            abort(403, 'Unauthorized action.');
        }
       $res= $referentiel->update([
            'is_active' => false,
        ]);

        return response()->noContent();
    }
}
