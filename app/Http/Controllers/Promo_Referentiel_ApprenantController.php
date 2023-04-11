<?php

namespace App\Http\Controllers;

use App\Http\Requests\Promo_Referentiel_ApprenantStoreRequest;
use App\Http\Requests\Promo_Referentiel_ApprenantUpdateRequest;
use App\Http\Resources\PromoReferentielApprenantCollection;
use App\Http\Resources\PromoReferentielApprenantResource;

use App\Models\PromoReferentielApprenant;
use App\Models\PromoReferentiel;


use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Promo_Referentiel_ApprenantController extends Controller
{
    public function index(Request $request): PromoReferentielApprenantCollection
    {
        $promoReferentielApprenants = PromoReferentielApprenant::all();

        return new PromoReferentielApprenantCollection($promoReferentielApprenants);
    }

    public function store(Promo_Referentiel_ApprenantStoreRequest $request): PromoReferentielApprenantResource
    {
        $promoReferentielApprenant = PromoReferentielApprenant::create($request->validated());

        return new PromoReferentielApprenantResource($promoReferentielApprenant);
    }

    public function show(Request $request, Promo_Referentiel_Apprenant $promoReferentielApprenant): PromoReferentielApprenantResource
    {
        return new PromoReferentielApprenantResource($promoReferentielApprenant);
    }

    public function update(Promo_Referentiel_ApprenantUpdateRequest $request, Promo_Referentiel_Apprenant $promoReferentielApprenant): PromoReferentielApprenantResource
    {
        $promoReferentielApprenant->update($request->validated());

        return new PromoReferentielApprenantResource($promoReferentielApprenant);
    }

    public function getApprenant(Request $request)
    {
        $promoReferentiel=PromoReferentiel::where([
            ['promo_id', '=',$request->promo_id],
            ['referentiel_id', '=', $request->referentiel_id]])->pluck('id');
          
        $promoReferentielApprenant= PromoReferentielApprenant::whereHas('apprenant', function ($query) {
            $query
            ->filter()
            ->whereIn('is_active', [1]);
        })->where('promo_referentiel_id', '=', $promoReferentiel)->paginate(request()->get('perpage', env('DEFAULT_PAGINATION')), ['*'], 'page');

        return new PromoReferentielApprenantCollection($promoReferentielApprenant);
    }


    public function destroy(Request $request, Promo_Referentiel_Apprenant $promoReferentielApprenant): Response
    {
        $promoReferentielApprenant->delete();

        return response()->noContent();
    }
}
