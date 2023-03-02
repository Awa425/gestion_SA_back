<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('promo', App\Http\Controllers\PromoController::class);
});

Route::middleware('auth:sanctum')->group(function(){
    Route::apiResource('promo', App\Http\Controllers\PromoController::class); 
    Route::apiResource('referentiel', App\Http\Controllers\ReferentielController::class);
});
Route::apiResource('referentiel', App\Http\Controllers\ReferentielController::class);
Route::apiResource('promo_-referentiel_-apprenant', App\Http\Controllers\Promo_Referentiel_ApprenantController::class);

Route::apiResource('apprenant', App\Http\Controllers\ApprenantController::class);

Route::apiResource('visiteur', App\Http\Controllers\VisiteurController::class);