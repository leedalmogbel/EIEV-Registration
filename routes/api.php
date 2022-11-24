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
use App\Http\Controllers\FhorseController;
use App\Http\Middleware\EnsureClientIsValid;
use App\Http\Middleware\EnsureClientIsFed;
use App\Models\Reusable;

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
});
Route::domain('localhost')->group(function(){
    Route::get('ridercheck',[FriderController::class,'checkEligibility']);
    Route::get('horsecheck',[FhorseController::class,'checkEligibility']);
    Route::get('getqrcode',[UserprofileController::class,'getQr']);
    Route::get('generateUniqueids',[UserprofileController::class,'generateUnique']);
    Route::get('execute',function(Request $request)
    {
       $data =  Reusable::soapCall('InsertEntries',[
            'params'=>[
                'pEvtCateg'=>$request->pEvtCateg,
                'pIdCode'=>$request->pIdCode,
                'pStartNo'=>$request->pStartNo,
                'pStartCode'=>$request->pStartCode,
                'pRiderName'=>$request->pRiderName,
                'pRiderFname'=>$request->pRiderFname,
                'pRiderLname'=>$request->pRiderLname,
                'pRiderLicenseFei'=>$request->pRiderLicenseFei,
                'pRiderLicenseEef'=>$request->pRiderLicenseEef,
                'pRiderNationality'=>$request->pRiderNationality,
                'pHorseName'=>$request->pHorseName,
                'pHorseYear'=>$request->pHorseYear,
                'pHorseGender'=>$request->pHorseGender,
                'pHorseColor'=>$request->pHorseColor,
                'pHorseBreed'=>$request->pHorseBreed,
                'pHorseLicenseFei'=>$request->pHorseLicenseFei,
                'pHorseLicenseEef'=>$request->pHorseLicenseEef,
                'pHorseChip'=>$request->pHorseChip,
                'pOwnerName'=>$request->pOwnerName,
                'pTrainerName'=>$request->pTrainerName,
                'pStableName'=>$request->pStableName,
                'pContactPerson'=>$request->pContactPerson,
                'pContactNumber'=>$request->pContactNumber,
                'pRiderImage'=>null,
                'pExecutedBy'=>$request->pExecutedBy,
                'pHorseOrigin'=>$request->pHorseOrigin,
                'pRiderGender'=>$request->pRiderGender,
                'pBarcodeValue'=>$request->pBarcodeValue
                ]
            ],
            [
                'pEvtCateg',
                'pIdCode',
                'pStartNo',
                'pStartCode',
                'pRiderName',
                'pRiderFname',
                'pRiderLname',
                'pRiderLicenseFei',
                'pRiderLicenseEef',
                'pRiderNationality',
                'pHorseName',
                'pHorseYear',
                'pHorseGender',
                'pHorseColor',
                'pHorseBreed',
                'pHorseLicenseFei',
                'pHorseLicenseEef',
                'pHorseChip',
                'pOwnerName',
                'pTrainerName',
                'pStableName',
                'pContactPerson',
                'pContactNumber',
                'pRiderImage',
                'pExecutedBy',
                'pHorseOrigin',
                'pRiderGender',
                'pBarcodeValue'
            ],
            '!InsertEntriesV2Result|result-Result|inserstatus-ErrorMessage|errormsg-Status|status-Remarks|remarks',
            true
        );
    });
});