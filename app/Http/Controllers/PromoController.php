<?php

namespace App\Http\Controllers;

use App\Http\Requests\PromoStoreRequest;
use App\Http\Requests\PromoUpdateRequest;
use App\Http\Resources\PromoCollection;
use App\Http\Resources\PromoResource;
use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index()
    {
        $promos = Promo::all();

        return new PromoCollection($promos);
    }

    public function store(PromoStoreRequest $request)
    {
        $promo = Promo::create($request->validated());

        return new PromoResource($promo);
    }

    public function show(Promo $promo)
    {
        return new PromoResource($promo);
    }

    public function update(PromoUpdateRequest $request, Promo $promo)
    {
        $promo->update($request->validated());

        return new PromoResource($promo);
    }

    public function destroy(Request $request, Promo $promo)
    {
        $promo->delete();

        return response()->noContent();
    }
}
