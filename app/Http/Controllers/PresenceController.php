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
    public function index(Request $request)
    {


        $perPage = $request->input('per_page', env('DEFAULT_PAGINATION', 10));

        $presences = Presence::all()->paginate($perPage);

        return new PresenceCollection($presences);
    }

    public function store(PresenceStoreRequest $request)
    {


        $presence = Presence::create($request->validated());

        return new PresenceResource($presence);
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
