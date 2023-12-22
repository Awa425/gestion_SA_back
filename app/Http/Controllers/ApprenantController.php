<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use App\Models\Absence;
use App\Models\Apprenant;
use App\Models\Referentiel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Notifications\SendMail;
use App\Models\PromoReferentiel;
use Maatwebsite\Excel\Facades\Excel;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Schema;
use App\Http\Resources\ApprenantResource;
use App\Models\PromoReferentielApprenant;
use App\Http\Resources\ApprenantCollection;
use App\Http\Requests\ApprenantIndexRequest;
use App\Http\Requests\ApprenantStoreRequest;
use App\Notifications\SendEventNotification;
use App\Http\Requests\ApprenantUpdateRequest;
use App\Http\Requests\import\ApprenantsImport;
use App\Http\Resources\PromoReferentielResource;

class ApprenantController extends Controller
{

    /**
      * @OA\Info(
 *     description="This is an example API",
 *     version="1.0.0",
 *     title="Example API"
 * )
     * @OA\Get(
     *    path="/api/apprenants",
     *    operationId="index",
     *    tags={"apprenants"},
     *    summary="Get list of apprenants",
     *    description="Get list of apprenants",
     *    security={{"bearerAuth":{}}},
     *    @OA\Parameter(name="limit", in="query", description="limit", required=false,
     *        @OA\Schema(type="integer")
     *    ),
     *    @OA\Parameter(name="page", in="query", description="the page number", required=false,
     *        @OA\Schema(type="integer")
     *    ),
     *    @OA\Parameter(name="order", in="query", description="order  accepts 'asc' or 'desc'", required=false,
     *        @OA\Schema(type="string")
     *    ),
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example="200"),
     *             @OA\Property(property="data",type="object")
     *          )
     *       )
     *  )
     */
    public function searchAppByEmailOrTel($param){
        $app=Apprenant::where('telephone',$param)->first();
        if ($app) {
            return new ApprenantResource($app);
        }
        return response([
            "message" => "Apprenant introuvable"
        ]);
    }
    public function index(Request $request)
    {
        return new ApprenantCollection(Apprenant::where('is_active','=',1)
            ->filter()
            ->paginate(request()->get('perpage', env('DEFAULT_PAGINATION')), ['*'], 'page')
           );

 }
  /**
     * @OA\Post(
     *      path="/api/apprenants",
     *      operationId="store",
     *      tags={"apprenants"},
     *      summary="Store apprenant in DB",
     *      description="Store apprenant in DB",
     *    security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"nom", "content", "status"},
     *            @OA\Property(property="nom", type="string", format="string", example="nom"),
     *            @OA\Property(property="prenom", type="string", format="string", example="prenom"),
     *            @OA\Property(property="email", type="string", format="string", example="email"),
     *            @OA\Property(property="date_naissance", type="date", format="string", example="date_naissance"),
     *            @OA\Property(property="telephone", type="string", format="string", example="telephone"),
     *            @OA\Property(property="cni", type="string", format="string", example="cni"),
     *            @OA\Property(property="genre", type="string", format="string", example="genre"),
     *         ),
     *      ),
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=""),
     *             @OA\Property(property="data",type="object")
     *          )
     *       )
     *  )
     */

 public static function getImageResize(Request $request)
 {
     $img = [];
     if (  count($request->files->keys()) > 0 && $request->hasFile($request->files->keys()[0])) {
         $file = $request->file($request->files->keys()[0]);
         $imageType = $file->getClientOriginalExtension();
         $image_resize = Image::make($file)->resize( 100, 100, function ( $constraint ) {
             $constraint->aspectRatio();
         })->encode( $imageType );
         $img[$request->files->keys()[0]] = $image_resize;
     }
     return $img;
 }





 public function generate_matricule($promo_libelle)
 {
     // Obtenez le préfixe de la promo en prenant la première lettre de chaque mot
     $promo_prefix = '';
     $promo_tabs = explode(' ', $promo_libelle);

     foreach ($promo_tabs as $promo_tab) {
         $promo_prefix .= strtoupper(substr($promo_tab, 0, 1));
     }

     // Obtenez le nombre d'apprenants actuels pour déterminer le numéro d'incrémentation
     $count = Apprenant::count() + 1;

     // Formatez le numéro d'incrémentation avec trois chiffres
     $formatted_count = str_pad($count, 3, '0', STR_PAD_LEFT);

     // Concaténez le préfixe de la promo avec le numéro d'incrémentation
     $matricule = $promo_prefix . '-' . $formatted_count;

     return $matricule;
 }



 public function store(ApprenantStoreRequest $request)
 {
     $data = $request->validatedAndFiltered();
     $data['password'] = array_key_exists('password', $data) ? $data['password'] : "Passer";
     $photo = $this->getImageResize($request);
     $data['photo'] = count($photo) > 0 ? $photo['photo'] : null;

     $data['password'] = bcrypt($data['password']);
     $data['user_id'] = auth()->user()->id;

     // Utiliser la méthode generate_matricule de l'instance d'ApprenantController
     $matricule = $this->generate_matricule($request->promo_libelle, $request->referentiel_libelle);
     $data['matricule'] = $matricule;

     $data['reserves'] = self::diff_array(
         $request->all(),
         $request->validated(),
         null,
         (new Apprenant())->getFillable()
     );

     // Insert into apprenant
     $apprenant = Apprenant::create($data);

     // Insert into promoReferentielApprenant
     $promoReferentiel = PromoReferentiel::where([
         ['promo_id', '=', $request->promo_id],
         ['referentiel_id', '=', $request->referentiel_id]
     ])->first();
     $apprenant->promoReferentiels()->attach($promoReferentiel);

     return new ApprenantResource($apprenant);

     // Envoi de l'email
     if (method_exists($apprenant, 'notify')) {
         $apprenant->notify(new SendMail($apprenant));
     }

     
 }



 public function storeExcel(Request $request)
    {
        $request->validate([
            "excel_file" => 'required|mimes:xlsx,csv,xls',
        ]);

        try {
            $file = $request->file('excel_file');
            $import = new ApprenantsImport();
            Excel::import($import, $file);

            // Envoi d'email pour chaque apprenant créé
            $apprenants = $import->getApprenants();

            
            return response()->json([
                'message' => 'Insertion en masse réussie',
            ], 201);
            
            foreach ($apprenants as $apprenant) {
                if (method_exists($apprenant, 'notify')) {
                    $apprenant->notify(new SendMail($apprenant));
                }
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // Gestion des erreurs lors de l'insertion
            if ($e->errorInfo[1] == 1062) {
                $message = "Erreur de duplication d'entrée";
            } else {
                $message = $e->getMessage();
            }

            return response()->json([
                'message' => 'Erreur lors de l\'insertion en masse : ' . $message,
            ], 401);
        } catch (\Exception $e) {
            // Gestion des autres exceptions
            return response()->json([
                'message' => 'Erreur lors de l\'insertion en masse : ' . $e->getMessage(),
            ], 401);
        }
    }


    public function searchApprenant($query)
    {
        $userTable = (new Apprenant())->getTable();

        if (Schema::hasColumn($userTable, 'nom')) {
            return new ApprenantCollection(
                Apprenant::where('nom', 'like', "%$query%")
                    ->orWhere('prenom', 'like', "%$query%")
                    ->orWhere('telephone', 'like', "%$query%")
                    ->orWhere('matricule', 'like', "%$query%")
                    ->get()
            );
        } else {
            // Gérer le cas où la colonne 'nom' n'existe pas
            return response()->json(['error' => 'Column nom not found'], 500);
        }
    }


  
 /**
     * @OA\Get(
     *    path="/api/apprenants/{id}",
     *    operationId="show",
     *    tags={"apprenants"},
     *    summary="Get Apprenant Detail",
     *    security={{"bearerAuth":{}}},
     *    description="Get Apprenant Detail",
     *    @OA\Parameter(name="id", in="path", description="Id of Apprenant", required=true,
     *        @OA\Schema(type="integer")
     *    ),
     *     @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *          @OA\Property(property="status_code", type="integer", example="200"),
     *          @OA\Property(property="data",type="object")
     *           ),
     *        )
     *       )
     *  )
     */

    public function show(Apprenant $apprenant)
    {
        $promoReferentielId=PromoReferentielApprenant::where(['apprenant_id'=> $apprenant->id])->first('promo_referentiel_id');
        $promoReferentiel= PromoReferentiel::where(['id'=> $promoReferentielId->promo_referentiel_id])->first();
        $apprenantAbsence= Absence::where(['apprenant_id'=> $apprenant->id])->get();

        return[
            "apprenant"=> new ApprenantResource($apprenant),
            "promoReferentiel"=> new PromoReferentielResource($promoReferentiel),
            "apprenantAbsence"=> $apprenantAbsence
        ];


    }

    public function search(Request $request)
    {

        $apprenant= Apprenant::where('matricule','=', $request->matricule)->first('id');


        return $apprenant;


    }

    public function reset(Request $request)
    {

        $apprenant = Apprenant::find($request->id);

        $apprenant->update([

            'password' => bcrypt("Passer"),

        ]);

        return $apprenant;


    }

     /**
     * @OA\Put(
     *     path="/api/apprenants/{id}",
     *     operationId="update",
     *     tags={"apprenants"},
     *     summary="Update apprenant in DB",
     *    security={{"bearerAuth":{}}},
     *     description="Update apprenant in DB",
     *     @OA\Parameter(name="id", in="path", description="Id of apprenant", required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *           required={"nom", "content", "status"},
     *           @OA\Property(property="nom", type="string", format="string", example="nom"),
     *            @OA\Property(property="prenom", type="string", format="string", example="prenom"),
     *            @OA\Property(property="email", type="string", format="string", example="email"),
     *            @OA\Property(property="date_naissance", type="date", format="string", example="date_naissance"),
     *            @OA\Property(property="telephone", type="string", format="string", example="telephone"),
     *            @OA\Property(property="cni", type="string", format="string", example="cni"),
     *            @OA\Property(property="genre", type="string", format="string", example="genre"),
     *        ),
     *     ),
     *     @OA\Response(
     *          response=200, description="Success",
     *          @OA\JsonContent(
     *             @OA\Property(property="status_code", type="integer", example="200"),
     *             @OA\Property(property="data",type="object")
     *          )
     *       )
     *  )
     */

    public function update(ApprenantUpdateRequest $request, Apprenant $apprenant)
    {


        $validatedData = $request->validatedAndFiltered();

        if (isset($validatedData['password'])) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        }

        $apprenant->update($validatedData);

        return new ApprenantResource($apprenant);
    }

    public function activateApprenant(Request $request, Apprenant $apprenant)
    {


        $apprenant->update([
            'is_active' => !$apprenant->is_active,
            'motif' => null,
        ]);

        return new ApprenantResource($apprenant);
    }


     /**
     * @OA\Delete(
     *    path="/api/apprenants/{id}",
     *    operationId="destroy",
     *    tags={"apprenants"},
     *    summary="Delete Apprenant",
     *    security={{"bearerAuth":{}}},
     *    description="Delete Apprenant",
     *    @OA\Parameter(name="id", in="path", description="Id of Apprenant", required=true,
     *        @OA\Schema(type="integer")
     *    ),
     *    @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *         @OA\Property(property="status_code", type="integer", example="200"),
     *         @OA\Property(property="data",type="object")
     *          ),
     *       )
     *      )
     *  )
     */
    public function destroy(Request $request, Apprenant $apprenant)
    {

        $apprenant->update([

            'is_active' => !$apprenant->is_active,
            'motif' => $request->motif,
        ]);

        return response()->json(['message' => 'Désactiver avec succès'], 200);
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
