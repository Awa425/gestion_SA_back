<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Schema;



class UserController extends Controller
{
     /**

     * @OA\Get(
     *    path="/api/users",
     *    operationId=" index  ",
     *    tags={"users"},
     *    summary="Get list of users",
     *    description="Get list of users",
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
    public function index(Request $request)
    {


        return new UserCollection(User::ignoreRequest(['perpage'])
        ->filter()
        // ->where('is_active', "=", 1)
        ->orderByDesc('isActive')
        ->paginate(request()
            ->get('perpage', env('DEFAULT_PAGINATION')), ['*'], 'page')
         );
    }

    /**
     * @OA\Post(
     *      path="/api/users",
     *      operationId=" store  ",
     *      tags={"users"},
     *      summary="Store user in DB",
     *      description="Store user in DB",
     *    security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *            required={"nom", "content", "status"},
     *            @OA\Property(property="nom", type="string", format="string", example="nom"),
     *            @OA\Property(property="prenom", type="string", format="string", example="prenom"),
     *            @OA\Property(property="email", type="string", format="string", example="email"),
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

    public function generate_matricule($role_id)
    {

        $role = Role::where('id', '=', $role_id)->select('libelle')->first();
        $role_prefix = $role['libelle'];
        $role_prefix =substr($role_prefix, 0, 3);
        $date = date('YmdHis') . number_format(microtime(true), 3, '', '');
        $matricule = $role_prefix . '_' . $date;
        return $matricule;
    }

    public function store(UserStoreRequest $request)
    {
        $data = $request->validatedAndFiltered();

        if (empty($data['password'])) {
            $data['password'] = bcrypt('passer');
        } else {
            $data['password'] = bcrypt($data['password']);
        }

        $data['user_id'] = auth()->user()->id;
        $data['matricule'] = $this->generate_matricule($data['role_id']);

        $user = User::create($data);

        return new UserResource($user);
    }

    /**
     * @OA\Get(
     *    path="/api/users/{id}",
     *    operationId=" show  ",
     *    tags={"users"},
     *    summary="Get user Detail",
     *    security={{"bearerAuth":{}}},
     *    description="Get user Detail",
     *    @OA\Parameter(name="id", in="path", description="Id of user", required=true,
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

    public function show(Request $request, User $user)
    {

        return new UserResource($user);
    }

     /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     operationId=" update  ",
     *     tags={"users"},
     *     summary="Update user in DB",
     *    security={{"bearerAuth":{}}},
     *     description="Update user in DB",
     *     @OA\Parameter(name="id", in="path", description="Id of user", required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *        required=true,
     *        @OA\JsonContent(
     *           required={"nom", "content", "status"},
     *            @OA\Property(property="nom", type="string", format="string", example="nom"),
     *            @OA\Property(property="prenom", type="string", format="string", example="prenom"),
     *            @OA\Property(property="email", type="string", format="string", example="email"),
     *         ),
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

    public function update(UserUpdateRequest $request, User $user)
    {
        $validatedData = $request->validatedAndFiltered();

        if (!empty($validatedData['password'])) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        }

        $user->update($validatedData);

        return new UserResource($user);
    }
/**
     * @OA\Delete(
     *    path="/api/users/{id}",
     *    operationId=" destroy  ",
     *    tags={"users"},
     *    summary="Delete user",
     *    security={{"bearerAuth":{}}},
     *    description="Delete user",
     *    @OA\Parameter(name="id", in="path", description="Id of user", required=true,
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
    public function destroy(Request $request, User $user)
    {

        $user->update([
            'isActive' => !$user->isActive,
        ]);

        return response()->json(['message' => 'Désactiver avec succès'], 200);
    }


    public function searchUser($query)
    {
        $userTable = (new User())->getTable();

        if (Schema::hasColumn($userTable, 'nom')) {
            return new UserCollection(
                User::where('nom', 'like', "%$query%")
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
    public function searchByName($name)
    {
        return new UserCollection(User::where('name', 'like', "%$name%")->get());
    }

    /**
     * Rechercher un utilisateur par prénom.
     *
     * @param string $prenom
     * @return UserCollection
     */
    public function searchByPrenom($prenom)
    {
        return new UserCollection(User::where('prenom', 'like', "%$prenom%")->get());
    }

    /**
     * Rechercher un utilisateur par numéro de téléphone.
     *
     * @param string $telephone
     * @return UserCollection
     */
    public function searchByTelephone($telephone)
    {
        return new UserCollection(User::where('telephone', 'like', "%$telephone%")->get());
    }

    /**
     * Rechercher un utilisateur par matricule.
     *
     * @param string $matricule
     * @return UserCollection
     */
    public function searchByMatricule($matricule)
    {
        return new UserCollection(User::where('matricule', 'like', "%$matricule%")->get());
    }
}
