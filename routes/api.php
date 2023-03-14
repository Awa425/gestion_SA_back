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




Route::middleware('auth:sanctum','userAuthorisation')->group(function(){
    Route::apiResources(
        [

            'promos'=> App\Http\Controllers\PromoController::class,
            'referentiels'=> App\Http\Controllers\ReferentielController::class,
            'apprenants'=> App\Http\Controllers\ApprenantController::class,
            'visiteurs'=> App\Http\Controllers\VisiteurController::class,
            ]
        );

    Route::group(['prefix' => 'apprenants'], function (){

        Route::post('ajout/excel' , [ApprenantController::class,'storeExcel']);
    });
});








