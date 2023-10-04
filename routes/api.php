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
use App\Http\Controllers\EntryController;
// use App\Http\Controllers\LentryController;
use App\Http\Controllers\FhorseController;
use App\Http\Middleware\EnsureClientIsValid;
use App\Http\Middleware\EnsureClientIsFed;
use App\Models\Reusable;
use Illuminate\Support\Facades\Artisan;
use App\Models\Multi;
use Illuminate\Support\Str;


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

Route::group(['prefix' => 'ajax'], function () {
    Route::get('searchrider', [FriderController::class, 'index']);
    Route::get('searchhorse', [FhorseController::class, 'index']);
    Route::get('searchevent', [FeventController::class, 'index']);
    Route::get('searchentry', [FentryControler::class, 'index']);
});

Route::get('eievsync', function (Request $request) {
    $process = $request->sync;
    $id = Str::uuid();
    $result = array();

    if ($process == 'events') {
        $data = (new FederationController)->geteieveventlist(new Request);
        if ($data) {
            info("`{$id}` - Check data count.");
            $dcount = count($data['events']['data']);
            if ($dcount > 0) {
                Multi::insertOrUpdate($data['events']['data'], 'fevents');
                info("`{$id}` - `{$dcount}` records synced.");
            }
            $result[$process]['message'] = "{$process} --- {$id} - {$dcount} records synced.";
        }
    }

    if ($process == 'entries') {
        $data = (new FederationController)->getentries(new Request);
        if ($data) {
            info("`{$id}` - Check data count.");
            $dcount = count($data['entries']['data']);
            if ($dcount > 0) {
                Multi::insertOrUpdate($data['entries']['data'], 'fentries');
                info("`{$id}` - `{$dcount}` records synced.");
            }
            $result[$process]['message'] = "{$process} --- {$id} - {$dcount} records synced.";
        }
    }

    if ($process == 'horses') {
        $data = (new FederationController)->searchhorselist(new Request);
        if ($data) {
            info("`{$id}` - Check data count.");
            $dcount = count($data['horses']['data']);
            if ($dcount > 0) {
                Multi::insertOrUpdate($data['horses']['data'], 'fhorses');
                info("`{$id}` - `{$dcount}` records synced.");
            }
            $result[$process]['message'] = "{$process} --- {$id} - {$dcount} records synced.";
        }
    }

    if ($process == 'riders') {
        $data = (new FederationController)->searchriderlist(new Request);
        if ($data) {
            info("`{$id}` - Check data count.");
            $dcount = count($data['riders']['data']);
            if ($dcount > 0) {
                Multi::insertOrUpdate($data['riders']['data'], 'friders');
                info("`{$id}` - `{$dcount}` records synced.");
            }
        }
        $result[$process]['message'] = "{$process} --- {$id} - {$dcount} records synced.";
    }

    if ($process == 'trainers') {
        $data = (new FederationController)->searchtrainerlist(new Request);
        if ($data) {
            info("`{$id}` - Check data count.");
            $dcount = count($data['trainers']['data']);
            if ($dcount > 0) {
                Multi::insertOrUpdate($data['trainers']['data'], 'ftrainers');
                info("`{$id}` - `{$dcount}` records synced.");
            }
        }
        $result[$process]['message'] = "{$process} --- {$id} - {$dcount} records synced.";
    }

    if ($process == 'owners') {
        $data = (new FederationController)->searchownerlist(new Request);
        if ($data) {
            info("`{$id}` - Check data count.");
            $dcount = count($data['owners']['data']);
            if ($dcount > 0) {
                Multi::insertOrUpdate($data['owners']['data'], 'fowners');
                info("`{$id}` - `{$dcount}` records synced.");
            }
        }
        $result[$process]['message'] = "{$process} --- {$id} - {$dcount} records synced.";
    }

    if ($process == 'stables') {
        $data = (new FederationController)->getstablelist(new Request);
        if ($data) {
            info("`{$id}` - Check data count.");
            $dcount = count($data['stables']['data']);
            if ($dcount > 0) {
                Multi::insertOrUpdate($data['stables']['data'], 'fstables');
                info("`{$id}` - `{$dcount}` records synced.");
            }
        }
        $result[$process]['message'] = "{$process} --- {$id} - {$dcount} records synced.";
    }

    return response()->json($result);
});


Route::group(['middleware' => [EnsureClientIsValid::class], 'prefix' => 'uaeerf'], function () {
    Route::post('addentry', [FederationController::class, 'addentry']);
    Route::post('eventlist', [FederationController::class, 'geteieveventlist']);
    Route::post('entries', [FederationController::class, 'getentries']);
    Route::post('userprofile', [FederationController::class, 'getuserprofile']);
    Route::post('horselist', [FederationController::class, 'searchhorselist']);
    Route::post('ownerlist', [FederationController::class, 'searchownerlist']);
    Route::post('riderlist', [FederationController::class, 'searchriderlist']);
    Route::post('trainerlist', [FederationController::class, 'searchtrainerlist']);
    Route::post('userlogin', [FederationController::class, 'userlogin']);
    Route::post('wslogin', [FederationController::class, 'wslogin']);
    Route::post('stablelist', [FederationController::class, 'getstablelist']);

    Route::post('execute', [FederationController::class, 'execute']);
});

Route::group(['middleware' => [EnsureClientIsFed::class], 'prefix' => 'uaeerf'], function () {
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

Route::domain('devregistration.eiev-app.ae')->group(function () {
    Route::get('generateUniqueids', [UserprofileController::class, 'generateUnique']);
    Route::get('startnum', [FentryControler::class, 'generateStartnumber']);
    Route::get('moveall', [FentryControler::class, 'moveall']);
    Route::get('addtopool', [SnpoolController::class, 'addToPool']);
    // Route::post('execute', [FederationController::class, 'execute']);
    Route::get('ridercheck', [FriderController::class, 'checkEligibility']);
    Route::get('horsecheck', [FhorseController::class, 'checkEligibility']);
    Route::get('entrycheck', [FentryControler::class, 'checkEligibility']);
    Route::get('processentry', [FentryControler::class, 'processEntry']);
    Route::get('getentries', [FentryControler::class, 'index']);
    Route::get('substituteentry', [EntryController::class, 'processSubstituteEntry']);
    Route::get('getprofiles', [UserprofileController::class, 'index']);
});
Route::get('entrysync', function (Request $request) {
    Artisan::call('command:syncentries --ip=' . $request->ip . ' --host=' . $request->host);
});
Route::get('assignno', [FentryControler::class, 'assignStartNo']);
Route::get('reserve', [FentryControler::class, 'reserveNumber']);
Route::get('getentry', [FentryControler::class, 'getEntry']);
Route::get('getnos', [FentryControler::class, 'getAvailSnos']);
// Route::domain('192.167.1.27:8000')->group(function(){
// Route::get('profilecloudsync', [LentryController::class, 'syncprofilesfromcloud']);
// Route::get('entrycloudsync', [LentryController::class, 'syncentriesfromcloud']);
// Route::get('execute', function (Request $request) {
//     $myRequest = new \Illuminate\Http\Request();
//     $myRequest->setMethod('POST');
//     $myRequest->request->add(
//         [
//             'action' => $request->action,
//             'includes' => $request->include,
//             'showraw' => $request->has('showraw') ? true : false,
//             'params' => $request->except(['action', 'showraw', 'pRiderLocation', 'include'])
//         ]
//     );
// $data = (new LentryController)->soapCall($myRequest);
// return response()->json($data);
// });

// Route::get('insertall', [LentryController::class, 'uploadAll']);
Route::get('ridercheck', [FriderController::class, 'checkEligibility']);
Route::get('horsecheck', [FhorseController::class, 'checkEligibility']);
Route::get('entrycheck', [FentryControler::class, 'checkEligibility']);
Route::get('processentry', [FentryControler::class, 'processEntry']);
Route::get('substituteentry', [EntryController::class, 'processSubstituteEntry']);
Route::get('getqrcode', [UserprofileController::class, 'getQr']);
Route::get('generateUniqueids', [UserprofileController::class, 'generateUnique']);
// });