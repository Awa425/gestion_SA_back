<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ApprenantController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [AuthController::class, 'login']);
Route::post('apprenant/login', [App\Http\Controllers\ApprenantAuth::class, 'login']);





Route::middleware('auth:sanctum','userAuthorisation')->group(function(){
    Route::apiResources(
        [

            'promos'=> App\Http\Controllers\PromoController::class,
            'referentiels'=> App\Http\Controllers\ReferentielController::class,
            'apprenants'=> App\Http\Controllers\ApprenantController::class,
            'visiteurs'=> App\Http\Controllers\VisiteurController::class,
            'user' => App\Http\Controllers\UserController::class,

            ]
        );
    Route::post('apprenants/{promo_id}/{referentiel_id}', [App\Http\Controllers\ApprenantController::class, 'store']);
    Route::get('promos/{promo_id}/{referentiel_id}', [App\Http\Controllers\Promo_Referentiel_ApprenantController::class, 'getApprenant']);
    Route::put('promos/detail/{promo_id}', [App\Http\Controllers\PromoController::class, 'addReferentiel']);
   // Route::get('promos/{promo_id}/{referentiel_id}', [App\Http\Controllers\PromoController::class, 'get_apprenants']);
  
   
   

    Route::group(['prefix' => 'apprenant'], function (){

        Route::post('ajout/excel' , [ApprenantController::class,'storeExcel']);
    });
});
