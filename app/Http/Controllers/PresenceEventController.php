<?php

namespace App\Http\Controllers;

use App\Http\Resources\presenceEventResource;
use App\Models\PresenceEvent;
use Illuminate\Http\Request;

class PresenceEventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return PresenceEvent::all()->load(["apprenants","event"]);
        return presenceEventResource::collection(PresenceEvent::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return PresenceEvent::firstOrCreate([
            'promo_referentiel_apprenant_id'=>$request->promoRefApp,
            'evenement_id'=>$request->evenement_id,
            'isPresent'=>0
        ]);
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
