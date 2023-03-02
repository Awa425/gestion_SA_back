<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApprenantStoreRequest;
use App\Http\Requests\ApprenantUpdateRequest;
use App\Http\Resources\ApprenantCollection;
use App\Http\Resources\ApprenantResource;
use App\Models\Apprenant;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ApprenantController extends Controller
{
    public function index(Request $request): ApprenantCollection
    {
        $apprenants = Apprenant::all();

        return new ApprenantCollection($apprenants);
    }

    public function store(ApprenantStoreRequest $request): ApprenantResource
    {
        $apprenant = Apprenant::create($request->validated());

        return new ApprenantResource($apprenant);
    }

    public function show(Request $request, Apprenant $apprenant): ApprenantResource
    {
        return new ApprenantResource($apprenant);
    }

    public function update(ApprenantUpdateRequest $request, Apprenant $apprenant): ApprenantResource
    {
        $apprenant->update($request->validated());

        return new ApprenantResource($apprenant);
    }

    public function destroy(Request $request, Apprenant $apprenant): Response
    {
        $apprenant->delete();

        return response()->noContent();
    }
}
