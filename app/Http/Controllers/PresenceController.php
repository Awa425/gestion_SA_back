<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Presence;
use App\Models\Apprenant;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\PresenceResource;
use App\Http\Resources\ApprenantResource;
use App\Http\Resources\PresenceCollection;
use App\Http\Requests\PresenceStoreRequest;
use App\Http\Requests\PresenceUpdateRequest;

class PresenceController extends Controller
{
    public function index(Request $request)
    {
        $presences =Presence::whereDate('date_heure_arriver', Carbon::today())->get();
        return new PresenceCollection($presences);
    }

    public function store(Request $request)
    {
        $matricule = $request->matricule;
        $apprenant = Apprenant::where('matricule', $matricule)->first();

        if (!$apprenant) {
            return response()->json(['error' => 'Apprenant not found'], 404);
        }

        $data['date_heure_arriver'] = $request->date_heure_arriver;
        $data['apprenant_id'] = $apprenant->id;

        $presence = Presence::create($data);

        return new ApprenantResource($apprenant);
    }


    public function show(Request $request, Presence $presence)
    {
        return new PresenceResource($presence);
    }

    public function update(PresenceUpdateRequest $request, Presence $presence)
    {

        $presence->update($request->validated());

        return new PresenceResource($presence);
    }

    public function destroy(Request $request, Presence $presence)
    {



        return response()->noContent();
    }

}
