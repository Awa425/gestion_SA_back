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
use Illuminate\Support\Str;


class UserController extends Controller
{
    public function index(Request $request)
    {
        if ((auth()->user()->cannot('manage') || auth()->user()->can('view')) && (auth()->user()->can('manage') || auth()->user()->cannot('view'))){
            return response([

                "message" => "vous n'avez pas le droit",

             ],401);
         
          }
 
        return new UserCollection(User::ignoreRequest(['perpage'])
        ->filter()
        ->where('isActive', '=', '1')
        ->paginate(env('DEFAULT_PAGINATION'), ['*'], 'page'));
          
    }

    public function generate_matricule($role_id)
    {

        $role = Role::where('id', '=', $role_id)->select('libelle')->first();
        $role_prefix = $role['libelle'];
        $role_prefix =substr($role_prefix, 0, 3);
        $date = date('Ymd');
        $matricule = $role_prefix . '_' . Str::random(5) . '_' . $date;
        return $matricule;
    }

    public function store(UserStoreRequest $request)
    {
        if ($request->user()->cannot('manage')) {
            return response([
                "message" => "vous n'avez pas le droit",
            ], 401);
        }

        $data = $request->validatedAndFiltered();

        $data['password'] = bcrypt($data['password']);
        $data['user_id'] = auth()->user()->id;

        $data['matricule'] = $this->generate_matricule($data['role_id']);
        return $data['matricule'];
        $user = User::create($data);

        return new UserResource($user);
    }

    public function show(Request $request, User $user)
    {
        if ((auth()->user()->cannot('manage') || auth()->user()->can('view')) && (auth()->user()->can('manage') || auth()->user()->cannot('view'))) {
            return response([

                "message" => "vous n'avez pas le droit",

            ], 401);
        }
        return new UserResource($user);
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        if ($request->user()->cannot('manage')) {
            return response([
                "message" => "vous n'avez pas le droit",
            ], 401);
        }

        $validatedData = $request->validatedAndFiltered();

        if (isset($validatedData['password'])) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        }
        $user->update($validatedData);

        return new UserResource($user);
    }

    public function destroy(Request $request, User $user): Response
    {
    if (auth()->user()->cannot('manage')) {
        return response([
            "message" => "vous n'avez pas le droit",
        ], 401);
    }
    $user->update([
        'isActive' => 0
    ]);

    return response()->noContent();
    }
}
