<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Presence;
use App\Models\Apprenant;
use App\Models\Referentiel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\PresenceResource;
use App\Http\Resources\ApprenantResource;
use App\Http\Resources\PresenceCollection;
use App\Http\Requests\PresenceStoreRequest;
use App\Http\Resources\ApprenantCollection;
use App\Http\Requests\PresenceUpdateRequest;

class PresenceController extends Controller
{
    public function index(Request $request)
    {

        $date = !empty($request->input('date')) ? $request->input('date') : Carbon::today();

        $presences = Presence::whereDate('date_heure_arriver', $date)
            ->with(['apprenants'])
            ->get();


        return new PresenceCollection($presences);
    }



    public function presencestotales(Request $request)
    {
        $date = !empty($request->input('date')) ? $request->input('date') : Carbon::today();

        $presences = DB::table('presences')
            ->whereDate('date_heure_arriver', $date)
            ->join('apprenant_presence', 'presences.id', '=', 'apprenant_presence.presence_id')
            ->join('apprenants', 'apprenant_presence.apprenant_id', '=', 'apprenants.id')
            ->join('promo_referentiel_apprenants', 'apprenants.id', '=', 'promo_referentiel_apprenants.apprenant_id')
            ->join('promo_referentiels', 'promo_referentiel_apprenants.promo_referentiel_id', '=', 'promo_referentiels.id')
            ->join('referentiels', 'promo_referentiels.referentiel_id', '=', 'referentiels.id')
            ->select('presences.date_heure_arriver', 'apprenants.*', 'referentiels.libelle as referentiel_libelle')
            ->get();

        return response()->json($presences);
    }



    public function store(Request $request)
    {

        $matricule = $request->matricule;
        $apprenant = Apprenant::where('matricule', $matricule)->first();

        if (!$apprenant) {
            return response()->json(['error' => 'Apprenant not found'], 404);
        }

        $presence = Presence::where('date_heure_arriver', Carbon::today())->first();
        if (!$presence) {
            $presence = new Presence();
            $presence->date_heure_arriver = Carbon::today();
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
