<?php

use App\Models\User;
use App\Models\Presence;
use App\Models\Apprenant;
use Illuminate\Http\Request;
use App\Models\PromoReferentiel;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Laravel\Sanctum\PersonalAccessToken;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PromoController;
use App\Http\Resources\ApprenantResource;
use App\Models\PromoReferentielApprenant;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\ApprenantController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PresenceEventController;
use App\Http\Resources\PromoReferentielResource;
use App\Http\Controllers\EmploieDuTempController;

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

Route::get('/apprenant', function (Request $request) {
    $user = explode(" ",$request->headers->all()["authorization"][0]);
    $token = PersonalAccessToken::findToken($user[1]);
    $apprenant = Apprenant::find($token->tokenable->id);
    $promoReferentielId=PromoReferentielApprenant::where(['apprenant_id'=> $apprenant->id])->first();
    $promoReferentiel= PromoReferentiel::where(['id'=> $promoReferentielId->promo_referentiel_id])->first();
    return[
        "apprenant"=> new ApprenantResource($apprenant),
        "promoReferentiel"=> new PromoReferentielResource($promoReferentiel)
    ];
 });


Route::middleware('auth:sanctum','userAuthorisation')->group(function(){

    Route::get('/user', function (Request $request) {
        return $request->user();
       });
       
    Route::get('promos/promoActuel',[PromoController::class, 'getPromoActuel']);
    Route::get('presenceEvent/mostPopularEvents',[PresenceEventController::class,'getMostPopularEvents']);

    Route::apiResources(
        [
            'promos'=> App\Http\Controllers\PromoController::class,
            'referentiels'=> App\Http\Controllers\ReferentielController::class,
            'apprenants'=> App\Http\Controllers\ApprenantController::class,
            'visiteurs'=> App\Http\Controllers\VisiteurController::class,
            'users' => App\Http\Controllers\UserController::class,
            'presences' => App\Http\Controllers\PresenceController::class,
            'absences' => App\Http\Controllers\AbsenceController::class,
            'prospections' => App\Http\Controllers\ProspectionController::class,
            'insertions' => App\Http\Controllers\InsertionApprenantController::class,
            'events'=>App\Http\Controllers\EventController::class,
            'presenceEvent'=>App\Http\Controllers\PresenceEventController::class,
            'emploieDuTemps'=>App\Http\Controllers\EmploieDuTempController::class,
            ]
        );
    Route::get('emploieDuTemps/ref/{idRef}/promo/{idPromo}', [EmploieDuTempController::class,'getCoursByIdRefAndIdPromo']);
    Route::get('promos/{id}/absence', [App\Http\Controllers\AbsenceController::class, 'getAbsence']);
    Route::post('presenceEvent/marquerPresence',[PresenceEventController::class,'marquerPresenceApp']);
    Route::post('presenceEvent/enleverPresence',[PresenceEventController::class,'enleverPresenceApp']);
    Route::post('presenceEvent/uploadInvites',[PresenceEventController::class,'storeInvitesExcel']);

    Route::post('apprenants/{promo_id}/{referentiel_id}', [App\Http\Controllers\ApprenantController::class, 'store']);
    Route::get('promos/detail/{promo_id}', [App\Http\Controllers\PromoController::class, 'Referentiel']);
    Route::get('pages/promos/detail/{promo_id}', [App\Http\Controllers\PromoController::class, 'ReferentielLinked']);

    Route::get('promos/{promo_id}/{referentiel_id}', [App\Http\Controllers\Promo_Referentiel_ApprenantController::class, 'getApprenant']);
    Route::get('promos/{promo_id}/{referentiel_id}/inactif', [App\Http\Controllers\Promo_Referentiel_ApprenantController::class, 'getApprenantNotActif']);
    Route::get('promos/{promo_id}/{referentiel_id}/absences', [App\Http\Controllers\AbsenceController::class, 'getAbsences']);

    Route::post('promos/{promo_id}/{referentiel_id}/inactif/{apprenant}', [App\Http\Controllers\ApprenantController::class, 'activateApprenant']);
    Route::put('promos/detail/{promo_id}', [App\Http\Controllers\PromoController::class, 'addReferentiel']);
    Route::put('pages/promos/detail/{promo_id}', [App\Http\Controllers\PromoController::class, 'removeReferentiel']);
    Route::post('apprenants/search', [App\Http\Controllers\ApprenantController::class, 'search']);
    Route::post('apprenants/reset', [App\Http\Controllers\ApprenantController::class, 'reset']);

    Route::get('apprenants/search/{param}' , [ApprenantController::class,'searchAppByEmailOrTel']);

    Route::group(['prefix' => 'apprenant'], function (){
        Route::post('ajout/excel' , [ApprenantController::class,'storeExcel']);
    });

    // Dashboard
    Route::get('dashboard/referentiels/apprenants', [DashboardController::class,'getnbrAppByRef']);
    Route::get('dashboard/promos/nonActive', [DashboardController::class, 'allPromoNonActiveAndApp']);
    Route::get('dashboard/promos', [DashboardController::class, 'promos']);
    Route::get('dashboard/apprenats', [DashboardController::class, 'apprenants']);
    Route::get('dashboard/apprenats/actuel', [DashboardController::class, 'apprenantActuel']);
    Route::get('dashboard/apprenats/feminin', [DashboardController::class, 'numbApprenantFeminin']);
    Route::get('dashboard/apprenats/masculin', [DashboardController::class, 'numbApprenantGarcon']);
    Route::get('dashboard/referenciel', [DashboardController::class, 'referenciel']);
    Route::get('dashboard/apprenats/feminin/{idPromo}', [DashboardController::class, 'getNumAppFemByPromoId']);
    Route::get('dashboard/apprenats/masculin/{idPromo}', [DashboardController::class, 'getNumAppMasByPromoId']);
    Route::get('dashboard/apprenats/{idPromo}',[DashboardController::class, 'getNumAppByPromo']);

    Route::put('events/{idEvent}/annulation',[EventController::class,'annulerEvent']);
    Route::put('events/{idEvent}/restauration',[EventController::class,'restoreEvent']);
});

Route::apiResource('/role',RoleController::class);
Route::get('/apprenantswithref', [PresenceController::class, 'presencestotales']);
Route::get('/searchUser/{query}',[UserController::class,'searchUser']);
Route::get('/searchStudent/{query}',[ApprenantController::class,'searchApprenant']);

Route::get('/searchUserByCall/{query}',[UserController::class,'searchByTelephone']);
Route::get('/searchUserByName/{query}',[UserController::class,'searchByName']);
Route::get('/searchUserByPrenom/{query}',[UserController::class,'searchByPrenom']);
Route::get('/searchUserByMatricule/{query}',[UserController::class,'searchByMatricule']);
