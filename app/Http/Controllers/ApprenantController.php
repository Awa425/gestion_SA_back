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
        if ((auth()->user()->cannot('manage') || auth()->user()->can('view')) && (auth()->user()->can('manage') || auth()->user()->cannot('view'))){
            return response([

                "message" => "vous n'avez pas le droit",

             ],401);
            }
        $apprenants = Apprenant::where('is_active','=','1')->get();

        return new ApprenantCollection($apprenants);
    }

    public function store(ApprenantStoreRequest $request)
    {
        if ($request->user()->cannot('manage')){
            return response([
                "message" => "vous n'avez pas le droit",
             ],401);
         
          }
 
        $user_id=array(
            "user_id" =>auth()->user()->id
        );
        
        $apprenants=$request->validated();
        $apprenants['password']=bcrypt($apprenants['password']);
        $result = array_merge($apprenants,$user_id);
        $apprenant = Apprenant::create($result);

        return new ApprenantResource($apprenant);
    }

    public function show(Request $request, Apprenant $apprenant)
    {
        if ((auth()->user()->cannot('manage') || auth()->user()->can('view')) && (auth()->user()->can('manage') || auth()->user()->cannot('view'))){
            return response([

                "message" => "vous n'avez pas le droit",

             ],401);
            }
        return new ApprenantResource($apprenant);
    }

    public function update(ApprenantUpdateRequest $request, Apprenant $apprenant)
    {
        if ($request->user()->cannot('manage')){
            return response([
                "message" => "vous n'avez pas le droit",
             ],401);
         
          }

          $apprenants=$request->validated();
          $apprenants['password']=bcrypt($apprenants['password']);
 
        $apprenant->update($apprenants);

        return new ApprenantResource($apprenant);
    }

    public function destroy(Request $request, Apprenant $apprenant): Response
    {
        if (auth()->user()->cannot('manage')){
            return response([
                "message" => "vous n'avez pas le droit",
             ],401);
         
          }
        $apprenant->update([
            'is_active' => 0
        ]);

        return response()->noContent();
    }
}
