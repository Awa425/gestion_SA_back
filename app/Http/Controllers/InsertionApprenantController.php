<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InsertionApprenant;
use App\Http\Requests\InsertionRequest;
use App\Http\Resources\InsertionResource;

class InsertionApprenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InsertionRequest $request)
    {
        //
        $insertion = InsertionApprenant::create($request->validated());
        return new InsertionResource($insertion);
    }

    /**
     * Display the specified resource.
     */
    public function show(InsertionApprenant $insertion)
    {
        return new InsertionResource($insertion);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
