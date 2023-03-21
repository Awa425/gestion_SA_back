<?php

namespace App\Http\Controllers;

use App\Models\Apprenant;
use App\Models\Referentiel;
use App\Models\Promo;
use App\Models\PromoReferentielApprenant;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\ApprenantIndexRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Resources\ApprenantResource;
use App\Http\Resources\ApprenantCollection;
use App\Http\Requests\ApprenantStoreRequest;
use App\Http\Requests\ApprenantUpdateRequest;
use App\Http\Requests\import\ApprenantsImport;
use App\Http\Resources\PromoReferentielApprenantCollection;
use App\Http\Resources\PromoReferentielApprenantResource;

class ApprenantController extends Controller
{
    public function index(Request $request)
    {

        return new PromoReferentielApprenantCollection(PromoReferentielApprenant::whereHas('apprenant', function ($query) {
            $query
            ->filter()
            ->whereIn('is_active', [1]);
        })->paginate(request()->get('perpage', env('DEFAULT_PAGINATION')), ['*'], 'page')
           );

 }



    public function generate_matricule($promo_id, $referentiel_id)
    {

        $promo = Promo::where('id', '=', $promo_id)->select('libelle')->first();
        $referentiel = Referentiel::where('id', '=', $referentiel_id)->select('libelle')->first();
        $lastRow = Apprenant::latest()->select('id')->first();


        $promo_tabs = explode(' ', $promo['libelle']);
        $referentiel_tabs = explode(' ', $referentiel['libelle']);
        $promo_prefix = '';
        $referentiel_prefix = '';

        foreach ($promo_tabs as $promo_tab) {
            $promo_prefix .= strtoupper(substr($promo_tab, 0, 1));
        }
        foreach ($referentiel_tabs as $referentiel_tab) {
            $referentiel_prefix .= strtoupper(substr($referentiel_tab, 0, 1));
        }
        $id=$lastRow['id'] + 1;
        $date = date('Ymd');
        $matricule = $promo_prefix . '_' . $referentiel_prefix . '_' . $id . '_' . $date;
        return $matricule;
    }

    public function store(ApprenantStoreRequest $request)
    {

        $data = $request->validatedAndFiltered();

        $data['password'] = bcrypt($data['password']);
        $data['user_id'] = auth()->user()->id;

        $data['matricule'] = $this->generate_matricule($request->promo_id, $request->referentiel_id);




        //insert into apprenant

        $apprenant = Apprenant::create($data);

        //insert into promoReferentielApprenant
        $promoReferentielApprenant = PromoReferentielApprenant::create([
            "promo_id" => $request->promo_id,
            "referentiel_id" => $request->referentiel_id,
            "apprenant_id" => $apprenant->id,
        ]);

        return new ApprenantResource($apprenant);
    }
    public function storeExcel(Request $request)
    {

        $request->validate([
            "excel_file" => 'required|mimes:xlsx,csv,xls',
        ]);


        $file = $request->file('excel_file');
        Excel::import(new ApprenantsImport(), $file);
        if (count($request->file()) > 0) {
            return response()->json([
                'message' => 'Insertion en masse reussie',
            ], 201);
        }
        return response()->json([
            'message' => 'Erreur lors de l\'insertion en masse',
        ], 401);
    }


    public function show(Apprenant $apprenant)
    {
        dd($apprenant);
        $ap = PromoReferentielApprenant::find($apprenant)->first();
        return new ApprenantResource($apprenant);
    }

    public function update(ApprenantUpdateRequest $request, Apprenant $apprenant)
    {


        $validatedData = $request->validatedAndFiltered();

        if (isset($validatedData['password'])) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        }

        $apprenant->update($validatedData);

        return new ApprenantResource($apprenant);
    }

    public function destroy(Request $request, Apprenant $apprenant): Response
    {

        $apprenant->update([
            'is_active' => 0
        ]);

        return response()->noContent();
    }
}
