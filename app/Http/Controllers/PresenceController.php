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

        $date = $request->input('date');

        if (!empty($date)) {
            $presences = Presence::whereDate('date_heure_arriver', $date)
                                ->with(['apprenants'])
                                ->get();
        } else {
            $presences = Presence::whereDate('date_heure_arriver', Carbon::today())
                                ->with(['apprenants'])
                                ->get();
        }

        return new PresenceCollection($presences);
    }


  




    public function store(Request $request)
    {
        $dateArrivee = Carbon::createFromFormat('Y-m-d H:i:s', $request->input('date_heure_arriver'));
        if (!$dateArrivee) {
            return response()->json(['error' => 'Invalid date format'], 400);
        }
        $matricule = $request->matricule;
        $apprenant = Apprenant::where('matricule', $matricule)->first();

        if (!$apprenant) {
            return response()->json(['error' => 'Apprenant not found'], 404);
        }
    
        $presence = Presence::where('date_heure_arriver', $dateArrivee)->first();
        if (!$presence) {
            $presence = new Presence();
            $presence->date_heure_arriver = $dateArrivee;
            $presence->save();
        }
    
        if ($presence->apprenants->contains($apprenant)) {
            return response()->json(['error' => 'Apprenant deja present'], 404);
        }
        $presence->apprenants()->attach($apprenant->id);
        
    
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
