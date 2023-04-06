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

    public function store(ReferentielStoreRequest $request)
    {
        $validatedData = $request->validated();
        $u= array('user_id' => auth()->user()->id);
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

    public function destroy(Request $request, Referentiel $referentiel)
    {
         $referentiel->update([
            'is_active' => !$referentiel->is_active,
        ]);
    
        return response()->json(['message' => 'Désactiver avec succès'], 200);
    }
    
}
