<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProspectionRequest;
use App\Http\Resources\ProspectionResource;
use App\Models\Prospection;
use Illuminate\Http\Request;

class ProspectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return ProspectionResource::collection(Prospection::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProspectionRequest $request)
    {
        //
        $prospection = Prospection::create($request->validated());
        return new ProspectionResource($prospection);
    }

    /**
     * Display the specified resource.
     */
    public function show(Prospection $prospection)
    {
        //
        dd($prospection->insertion());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prospection $prospection)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prospection $prospection)
    {
        //
    }
}
