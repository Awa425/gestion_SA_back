<?php

namespace App\Http\Controllers;

use App\Http\Requests\PresenceStoreRequest;
use App\Http\Requests\PresenceUpdateRequest;
use App\Http\Resources\PresenceCollection;
use App\Http\Resources\PresenceResource;
use App\Models\Presence;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PresenceController extends Controller
{
    public function index(Request $request): Response
    {
        if ((auth()->user()->cannot('manage') || auth()->user()->can('view')) && (auth()->user()->can('manage') || auth()->user()->cannot('view'))){
            abort(403, 'Unauthorized action.');
         
        }
        $presences = Presence::all();

        return new PresenceCollection($presences);
    }

    public function store(PresenceStoreRequest $request)
    {
        
        if (auth()->user()->cannot('manage_promo')) {
            abort(403, 'Unauthorized action.');
        }
        $presence = Presence::create($request->validated());

        return new PresenceResource($presence);
    }

    public function show(Request $request, Presence $presence): Response
    {
        return new PresenceResource($presence);
    }
    
    public function update(PresenceUpdateRequest $request, Presence $presence): Response
    {
        if (auth()->user()->cannot('manage')) {
            abort(403, 'Unauthorized action.');
        }
        $presence->update($request->validated());

        return new PresenceResource($presence);
    }

    public function destroy(Request $request, Presence $presence): Response
    {
        if (auth()->user()->cannot('manage')) {
            abort(403, 'Unauthorized action.');
        }
        

        return response()->noContent();
    }
}
