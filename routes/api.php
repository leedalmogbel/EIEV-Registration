<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FederationController;
use App\Http\Middleware\EnsureClientIsValid;


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
