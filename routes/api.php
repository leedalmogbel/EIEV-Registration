<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FederationController;
use App\Http\Controllers\FederationSyncController;
use App\Http\Middleware\EnsureClientIsValid;
use App\Http\Middleware\EnsureClientIsFed;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware'=>[EnsureClientIsValid::class],'prefix'=>'uaeerf'],function () {
    Route::post('addentry', [FederationController::class, 'addentry']);
    Route::post('eventlist', [FederationController::class, 'geteieveventlist']);
    Route::post('entries', [FederationController::class, 'getentries']);
    Route::post('userprofile', [FederationController::class, 'getuserprofile']);
    Route::post('horselist', [FederationController::class, 'searchhorselist']);
    Route::post('ownerlist', [FederationController::class, 'searchownerlist']);
    Route::post('riderlist', [FederationController::class, 'searchriderlist']);
    Route::post('trainerlist', [FederationController::class, 'searchtrainerlist']);
    Route::post('userlogin', [FederationController::class, 'userlogin']);
    Route::post('wslogin',[FederationController::class, 'wslogin']);
    Route::post('stablelist',[FederationController::class, 'getstablelist']);

    Route::post('execute', [FederationController::class, 'execute']);
});

Route::group(['middleware'=>[EnsureClientIsFed::class],'prefix'=>'sync'],function () {
    Route::post('GetEIEVEventList', [FederationSyncController::class, 'syncevents']);
    Route::post('GetEntries', [FederationSyncController::class, 'syncentries']);
    Route::post('GetUserProfile', [FederationSyncController::class, 'syncprofiles']);
    Route::post('SearchHorseListV5', [FederationSyncController::class, 'synchorses']);
    Route::post('SearchOwnerListV5', [FederationSyncController::class, 'syncowners']);
    Route::post('SearchRiderListV5', [FederationSyncController::class, 'syncriders']);
    Route::post('SearchTrainerListV5', [FederationSyncController::class, 'synctrainers']);
    Route::post('getStableList',[FederationSyncController::class, 'syncstables']);
});
