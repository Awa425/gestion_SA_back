<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReferentielStoreRequest;
use App\Http\Requests\ReferentielUpdateRequest;
use App\Http\Resources\ReferentielCollection;
use App\Http\Resources\ReferentielResource;
use App\Models\Referentiel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReferentielController extends Controller
{
    public function index(Request $request)
    {
        $referentiels = Referentiel::all();

        return new ReferentielCollection($referentiels);
    }

    public function store(ReferentielStoreRequest $request)
    {
        $referentiel = Referentiel::create($request->validated());

        return new ReferentielResource($referentiel);
    }

    public function show(Request $request, Referentiel $referentiel)
    {
        return new ReferentielResource($referentiel);
    }

    public function update(ReferentielUpdateRequest $request, Referentiel $referentiel)
    {
        $referentiel->update($request->validated());

        return new ReferentielResource($referentiel);
    }

    public function destroy(Request $request, Referentiel $referentiel)
    {
        $referentiel->delete();

        return response()->noContent();
    }
}
