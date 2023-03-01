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
    public function index()
    {
       $referentiels = Referentiel::where('is_active', true)->get();

        return new ReferentielCollection($referentiels);
    }

    public function store(ReferentielStoreRequest $request)
    {
        $user = auth()->user();
        $validate = $request->validated();
        $referentiel = Referentiel::create([
            'libelle' => $validate['libelle'],
            'description' => $validate['description'],
            'userid' => $user->id,
        ]
        );

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
       $res= $referentiel->update([
            'is_active' => false,
        ]);

        return response()->noContent();
    }
}
