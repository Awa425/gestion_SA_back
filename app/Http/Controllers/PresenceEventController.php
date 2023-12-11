<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use App\Models\Apprenant;
use Illuminate\Http\Request;
use App\Models\PresenceEvent;
use App\Models\PromoReferentiel;
use App\Models\PromoReferentielApprenant;
use App\Http\Resources\presenceEventResource;
use App\Imports\InvitesImport;
use Maatwebsite\Excel\Facades\Excel;

class PresenceEventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return presenceEventResource::collection(PresenceEvent::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $presenceEvent= PresenceEvent::firstOrCreate([
            'apprenant_id'=>$request->apprenant_id ,
            'evenement_id'=>$request->evenement_id,
            'nom'=>$request->nom,
            "prenom"=>$request->prenom,
            "email"=>$request->email,
            "telephone"=>$request->telephone,
            "cni"=>$request->cni,
            "genre"=>$request->genre,
            'is_present'=>0
        ]);
        return new presenceEventResource($presenceEvent);

    }
    public function marquerPresenceApp(Request $request){
    
        PresenceEvent::whereIn('id', $request->presenceEventIds)
                      ->update(['is_present'=>1]);
    }
    public function enleverPresenceApp(Request $request){
        PresenceEvent::where('id', $request->idEvent)
                      ->update(['is_present'=>0]);
    }
    public function storeInvitesExcel(Request $request){
        $request->validate([
            'invitesFile' => 'required|mimes:xlsx,xls,csv',
        ]);
        try{
            $file = $request->file('invitesFile');
            Excel::import(new InvitesImport, $file);
            return response([
                "message" => 'importation fait avec succès !'
            ]);
        }catch (\Illuminate\Database\QueryException $e) {
            // Gestion des erreurs lors de l'insertion
            if ($e->errorInfo[1] == 1062) {
                $message = "Erreur de duplication d'entrée";
            } else {
                $message = $e->getMessage();
            }

            return response()->json([
                'message' => 'Erreur lors de l\'insertion en masse : ' . $message,
            ], 401);
        }catch (\Exception $e) {
            // Gestion des autres exceptions
            return response()->json([
                'message' => 'Erreur lors de l\'insertion en masse : ' . $e->getMessage(),
            ], 401);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(PresenceEvent $presenceEvent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PresenceEvent $presenceEvent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PresenceEvent $presenceEvent)
    {
        //
    }
}
