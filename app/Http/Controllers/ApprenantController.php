<?php

namespace App\Http\Controllers;

use App\Models\Apprenant;
use App\Models\Referentiel;
use App\Models\Promo;
use App\Models\PromoReferentielApprenant;
use App\Models\PromoReferentiel;
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
    public function __construct()
    {
    }
    public function index(Request $request)
    {

        return new PromoReferentielApprenantCollection(PromoReferentielApprenant::whereHas('apprenant', function ($query) {
            $query
            ->filter()
            ->whereIn('is_active', [1]);
        })->paginate(request()->get('perpage', env('DEFAULT_PAGINATION')), ['*'], 'page')
           );

 }



    public static function generate_matricule($promo_libelle, $referentiel_libelle)
    {


        $promo_tabs = explode(' ', $promo_libelle);
        $referentiel_tabs = explode(' ', $referentiel_libelle);
        $promo_prefix = '';
        $referentiel_prefix = '';

        foreach ($promo_tabs as $promo_tab) {
            $promo_prefix .= strtoupper(substr($promo_tab, 0, 1));
        }
        foreach ($referentiel_tabs as $referentiel_tab) {
            $referentiel_prefix .= strtoupper(substr($referentiel_tab, 0, 1));
        }
        $date = date('YmdHis') . number_format(microtime(true), 3, '', '');
        $matricule = $promo_prefix . '_' . $referentiel_prefix . '_'  . $date;
        return $matricule;
    }

    public function store(ApprenantStoreRequest $request)
    {


        $data = $request->validatedAndFiltered();

        $data['password'] = bcrypt($data['password']);
        $data['user_id'] = auth()->user()->id;
        $promo = Promo::where('id', '=', $request->promo_id)->select('libelle')->first();
        $referentiel = Referentiel::where('id', '=', $request->referentiel_id)->select('libelle')->first();
        $data['matricule'] = $this->generate_matricule($promo['libelle'], $referentiel['libelle']);
        $data['reserves'] = self::diff_array($request->all(), $request->validated(), null, (new Apprenant())->getFillable());



        //insert into apprenant

        $apprenant = Apprenant::create($data);

        //insert into promoReferentielApprenant
        $promoReferentiel = PromoReferentiel::where([
            ['promo_id', '=', $request->promo_id],
            ['referentiel_id', '=', $request->referentiel_id]
        ])->first();
        $apprenant->promoReferentiels()->attach($promoReferentiel);
        

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

        return new PromoReferentielApprenantResource(PromoReferentielApprenant::whereHas('apprenant', function ($query) use ($apprenant) {
            $query->where('id', $apprenant['id']);
        })->first());

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


    public static function diff_array(array $tab1, array $tab2, $object = null, $arrayKeys = [])
    {
        $reserves = array_diff_key($tab1, $tab2);
        return self::transformToReserved($reserves, $object, $arrayKeys);
    }

    public static function transformToReserved($array, $object = null, array $arrayKeys = [])
    {

        $reserve = "";

        $keys = $arrayKeys;

        if ($object) {
            $keys = array_keys((array) $object);
        }

        foreach ($array as $key => $value) {
            if (is_string($value) && !in_array($key, $keys)) {
                $reserve .= $key . env('SEPARATOR_LABEL') . $value . env('SEPARATOR');
            }
        }
        return $reserve;
    }
}
