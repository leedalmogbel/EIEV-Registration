<?php

use Illuminate\Support\Facades\Route;

use App\Http\Middleware\SessionChecker;
use App\Http\Controllers\FentryControler;
use App\Http\Controllers\FeventController;
$defaultRoutes = [
    'get:/create' => [
        'createForm',
        ['sessionChecker:superadmin'],
    ],
    'post:/create' => [
        'create',
        ['sessionChecker:superadmin'],
    ],
    'get:/' => [
        'listing',
        ['sessionChecker:superadmin,admin'],
    ],
    'get:/statusUpdate/{status}/{id}' => [
        'statusUpdate',
        ['sessionChecker:superadmin, admin'],
    ],
    'get:/detail/{id}' => [
        'detail',
        ['sessionChecker:superadmin,admin'],
    ],
    'get:/update/{id}' => [
        'updateForm',
        ['sessionChecker:superadmin'],
    ],
    'post:/update/{id}' => [
        'update',
        ['sessionChecker:superadmin'],
    ]
];

$adminRoutes = [
    'season' => $defaultRoutes,
    'event'  => $defaultRoutes,
    'stable' => $defaultRoutes,
    'owner' => $defaultRoutes,
    'trainer' => $defaultRoutes,
    'rider' => $defaultRoutes,
    'horse' => $defaultRoutes,
    'race' => $defaultRoutes,
    'entry' => $defaultRoutes,
];

/* 
|------------------------------------------------------------------------
| Set you custom routes below
|-----------------------------------------------------------------------
 */

// race
$adminRoutes['race']['get:/'][1] = ['sessionChecker:superadmin,admin,user'];

// entry
$adminRoutes['entry']['get:/create'][1] = ['sessionChecker:superadmin,admin,user'];
$adminRoutes['entry']['post:/create'][1] = ['sessionChecker:superadmin,admin,user'];
$adminRoutes['entry']['get:/'][1] = ['sessionChecker:superadmin,admin,user'];

// horse
$adminRoutes['horse']['get:/create'][1] = ['sessionChecker:superadmin,admin,user'];
$adminRoutes['horse']['post:/create'][1] = ['sessionChecker:superadmin,admin,user'];
$adminRoutes['horse']['get:/'][1] = ['sessionChecker:superadmin,admin,user'];
$adminRoutes['horse']['get:/update/{id}'][1] = ['sessionChecker:superadmin,admin,user'];
$adminRoutes['horse']['post:/update/{id}'][1] = ['sessionChecker:superadmin,admin,user'];

// horse
$adminRoutes['horse']['get:/create'][1] = ['sessionChecker:superadmin,admin,user'];
$adminRoutes['horse']['post:/create'][1] = ['sessionChecker:superadmin,admin,user'];
$adminRoutes['horse']['get:/'][1] = ['sessionChecker:superadmin,admin,user'];
$adminRoutes['horse']['get:/update/{id}'][1] = ['sessionChecker:superadmin,admin,user'];
$adminRoutes['horse']['post:/update/{id}'][1] = ['sessionChecker:superadmin,admin,user'];

// trainer
$adminRoutes['trainer']['get:/create'][1] = ['sessionChecker:superadmin,admin,user'];
$adminRoutes['trainer']['post:/create'][1] = ['sessionChecker:superadmin,admin,user'];
$adminRoutes['trainer']['get:/'][1] = ['sessionChecker:superadmin,admin,user'];
$adminRoutes['trainer']['get:/update/{id}'][1] = ['sessionChecker:superadmin,admin,user'];
$adminRoutes['trainer']['post:/update/{id}'][1] = ['sessionChecker:superadmin,admin,user'];

// horse
$adminRoutes['owner']['get:/create'][1] = ['sessionChecker:superadmin,admin,user'];
$adminRoutes['owner']['post:/create'][1] = ['sessionChecker:superadmin,admin,user'];
$adminRoutes['owner']['get:/'][1] = ['sessionChecker:superadmin,admin,user'];
$adminRoutes['owner']['get:/update/{id}'][1] = ['sessionChecker:superadmin,admin,user'];
$adminRoutes['owner']['post:/update/{id}'][1] = ['sessionChecker:superadmin,admin,user'];

// horse
$adminRoutes['rider']['get:/create'][1] = ['sessionChecker:superadmin,admin,user'];
$adminRoutes['rider']['post:/create'][1] = ['sessionChecker:superadmin,admin,user'];
$adminRoutes['rider']['get:/'][1] = ['sessionChecker:superadmin,admin,user'];
$adminRoutes['rider']['get:/update/{id}'][1] = ['sessionChecker:superadmin,admin,user'];
$adminRoutes['rider']['post:/update/{id}'][1] = ['sessionChecker:superadmin,admin,user'];

// horse
$adminRoutes['stable']['get:/create'][1] = ['sessionChecker:superadmin,admin,user'];
$adminRoutes['stable']['post:/create'][1] = ['sessionChecker:superadmin,admin,user'];
$adminRoutes['stable']['get:/'][1] = ['sessionChecker:superadmin,admin,user'];
$adminRoutes['stable']['get:/update/{id}'][1] = ['sessionChecker:superadmin,admin,user'];
$adminRoutes['stable']['post:/update/{id}'][1] = ['sessionChecker:superadmin,admin,user'];
//echo '<pre>'; print_r($adminRoutes); exit;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if (Auth::viaRemember() || Auth::check()) {
        return redirect()->intended('dashboard');
    }else{
        if(session()->get('user')==null){
            return redirect('/login');
        }
        return redirect('/dashboard');
    }
})->middleware(SessionChecker::class);


Route::get('/login', 'UserController@loginForm');
Route::post('/login', 'UserController@login');

Route::get('/register', 'UserController@registerForm');
Route::post('/register', 'UserController@register');

Route::get('/role', 'RoleController@listing');
Route::get('/role/update/{id}', 'RoleController@updateForm');

foreach ($adminRoutes as $modelControl => $routes) {
    $controller = ucwords($modelControl);
    $controller .= "Controller";

    foreach ($routes as $methodRoute => $actions) {
        list($controlMethod, $middleware) = $actions;
        list($method, $route) = explode(':', $methodRoute);
        
        Route::$method($modelControl . $route, "{$controller}@{$controlMethod}")->middleware($middleware);
    }
    
    /*Route::get("/$route/create", "{$controller}@createForm");
    Route::post("/$route/create", "{$controller}@create");
    Route::get("/$route", "{$controller}@listing");
    Route::get("/$route/statusUpdate/{status}/{id}", "{$controller}@statusUpdate");
    Route::get("/$route/detail/{id}", "{$controller}@detail");
    Route::get("/$route/update/{id}", "{$controller}@updateForm");
    Route::post("/$route/update/{id}", "{$controller}@update");*/
}
//
Route::domain('devregistration.eiev-app.ae')->group(function ()
{
    Route::group(['prefix'=>'rideslist'],function ()
    {
        Route::get('/',[FentryControler::class,'getlists']);
        Route::get('/accept',[FentryControler::class,'accept']);
        Route::get('/mainlist',[FentryControler::class,'mainlist']);
        Route::get('/reject',[FentryControler::class,'reject']);
        Route::get('/withdraw',[FentryControler::class,'withdraw']);
    });
    Route::group(['prefix'=>'submitentry'],function ()
    {
        Route::get('/',[FentryControler::class,'entryadd']);
        Route::get('/add',[FentryControler::class,'addentry']);
    });
    Route::group(['prefix'=>'actions'],function ()
    {
        Route::get('/',[FentryControler::class,'actions']);
        Route::get('/add',[FentryControler::class,'addentry']);
    });

    Route::get('/changeentry',[FentryControler::class,'changeEntry']);
});
Route::domain('localhost')->group(function ()
{
    Route::group(['prefix'=>'rideslist'],function ()
    {
        Route::get('/',[FentryControler::class,'getlists']);
        Route::get('/accept',[FentryControler::class,'accept']);
        Route::get('/mainlist',[FentryControler::class,'mainlist']);
        Route::get('/reject',[FentryControler::class,'reject']);
        Route::get('/withdraw',[FentryControler::class,'withdraw']);
    });
    Route::group(['prefix'=>'submitentry'],function ()
    {
        Route::get('/',[FentryControler::class,'entryadd']);
        Route::get('/add',[FentryControler::class,'addentry']);
    });
    Route::group(['prefix'=>'actions'],function ()
    {
        Route::get('/',[FentryControler::class,'actions']);
        Route::get('/add',[FentryControler::class,'addentry']);
    });
    Route::get('/changeentry',[FentryControler::class,'changeEntry']);
});

// custom routes
Route::get('/entry/user/{userId}', "EntryController@horseRider");
Route::get('/logout', function () {
    session()->forget(['user', 'role']);
    return redirect('/login');
});

Route::get('/dashboard', "DashboardController@index");
Route::get('/generate-pdf', "DashboardController@entriesPDF");
Route::get('/entry/withdrawn', "EntryController@withdrawn");
Route::get('/me', 'UserController@me');
Route::get('/qrcode', 'UserController@downloadQRCode');
Route::get('/entry/entrychange', 'EntryController@changeEntryForm');
Route::get('/entry/entryswap', 'EntryController@swapEntryForm');
