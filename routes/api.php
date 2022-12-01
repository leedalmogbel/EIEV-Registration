<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FederationController;
use App\Http\Controllers\UserprofileController;
use App\Http\Controllers\FederationSyncController;
use App\Http\Controllers\FriderController;
use App\Http\Controllers\FeventController;
use App\Http\Controllers\SnpoolController;
use App\Http\Controllers\FentryControler;
use App\Http\Controllers\LentryController;
use App\Http\Controllers\FhorseController;
use App\Http\Middleware\EnsureClientIsValid;
use App\Http\Middleware\EnsureClientIsFed;
use App\Models\Reusable;
use Illuminate\Support\Facades\Artisan;

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

Route::group(['prefix'=>'ajax'],function ()
{
    Route::get('searchrider',[FriderController::class,'index']);
    Route::get('searchhorse',[FhorseController::class,'index']);
    Route::get('searchevent',[FeventController::class,'index']);
});

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

Route::group(['middleware'=>[EnsureClientIsFed::class],'prefix'=>'uaeerf'],function () {
    Route::post('/sync', [FederationSyncController::class, 'syncdata']);
    // Route::post('GetEIEVEventList', [FederationSyncController::class, 'syncevents']);
    // Route::post('GetEntries', [FederationSyncController::class, 'syncentries']);
    // Route::post('GetUserProfile', [FederationSyncController::class, 'syncprofiles']);
    // Route::post('SearchHorseListV5', [FederationSyncController::class, 'synchorses']);
    // Route::post('SearchOwnerListV5', [FederationSyncController::class, 'syncowners']);
    // Route::post('SearchRiderListV5', [FederationSyncController::class, 'syncriders']);
    // Route::post('SearchTrainerListV5', [FederationSyncController::class, 'synctrainers']);
    // Route::post('getStableList',[FederationSyncController::class, 'syncstables']);
});

Route::domain('devregistration.eiev-app.ae')->group(function(){
    Route::get('generateUniqueids',[UserprofileController::class,'generateUnique']);
    Route::get('startnum',[FentryControler::class,'generateStartnumber']);
    Route::get('moveall',[FentryControler::class,'moveall']);
    Route::get('addtopool',[SnpoolController::class,'addToPool']);
    // Route::post('execute', [FederationController::class, 'execute']);
    Route::get('ridercheck',[FriderController::class,'checkEligibility']);
    Route::get('horsecheck',[FhorseController::class,'checkEligibility']);
    Route::get('getentries',[FentryControler::class,'index']);
    Route::get('getprofiles',[UserprofileController::class,'index']);
});
Route::get('entrysync',function (Request $request)
{
    Artisan::call('command:syncentries --ip='.$request->ip.' --host='.$request->host);
});
Route::get('assignno',[FentryControler::class,'assignStartNo']);
Route::get('reserve',[FentryControler::class,'reserveNumber']);
Route::get('getentry',[FentryControler::class,'getEntry']);
Route::get('getnos',[FentryControler::class,'getAvailSnos']);
// Route::domain('192.167.1.27:8000')->group(function(){
    Route::get('profilecloudsync',[LentryController::class,'syncprofilesfromcloud']);
    Route::get('entrycloudsync',[LentryController::class,'syncentriesfromcloud']);
    Route::get('execute',function(Request $request)
    {
        $myRequest = new \Illuminate\Http\Request();
        $myRequest->setMethod('POST');
        $myRequest->request->add([
            'action'=>$request->action,
            'includes'=>$request->include,
            'showraw'=>$request->has('showraw') ? true:false,
            'params'=>$request->except(['action','showraw','pRiderLocation','include'])
            ]
        );
        $data = (new LentryController)->soapCall($myRequest);
        return response()->json($data);
    });
    Route::get('insertall',[LentryController::class,'uploadAll']);
    Route::get('ridercheck',[FriderController::class,'checkEligibility']);
    Route::get('horsecheck',[FhorseController::class,'checkEligibility']);
    Route::get('getqrcode',[UserprofileController::class,'getQr']);
    Route::get('generateUniqueids',[UserprofileController::class,'generateUnique']);
// });