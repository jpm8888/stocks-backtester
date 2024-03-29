<?php


use App\ModelBhavCopyCM;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

include_once 'web_common.php';
include_once 'web_routes_utils.php';

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/login_with_kite', 'HomeController@login_with_kite')->name('login_with_kite');
Route::get('/logout_kite', 'HomeController@logout_kite')->name('logout_kite');


//Route::get('/send_email', 'MailController@send_basic_email')->name('send_email');

Route::get('/refresh_instruments', 'HomeController@refresh_instruments')->name('refresh_instruments');


Route::group(['middleware' => ['auth'], 'prefix' => 'user', 'as' => 'user.'], function () {
    Route::get('change/password', 'Auth\ControllerChangePassword@index')->name('password');
    Route::post('change/password/update', 'Auth\ControllerChangePassword@update')->name('password.update');
});


Route::group(['middleware' => ['auth'], 'prefix' => 'feature', 'as' => 'feature.'], function () {
    Route::get('index', 'ControllerFeatureRequest@index')->name('index');
    Route::post('create', 'ControllerFeatureRequest@create')->name('create');
});


Route::group(['middleware' => ['auth'], 'prefix' => 'watchlist', 'as' => 'watchlist.'], function () {
    Route::get('index', 'ControllerWatchlist@index')->name('index');
});

Route::group(['middleware' => ['auth'], 'prefix' => 'nifty_live_oi', 'as' => 'nifty_live_oi.'], function () {
    Route::get('index', 'ControllerNiftyLiveOI@index')->name('index');
    Route::get('fetch', 'ControllerNiftyLiveOI@fetch')->name('fetch');
});


Route::group(['middleware' => ['auth'], 'prefix' => 'releases', 'as' => 'releases.'], function () {
    Route::get('index', 'ControllerReleases@index')->name('index');
    Route::post('create', 'ControllerReleases@create')->name('create');
});


Route::group(['middleware' => ['auth'], 'prefix' => 'risk_calculator', 'as' => 'risk_calculator.'], function () {
    Route::get('index', 'ControllerRiskCalculator@index')->name('index');
});

Route::group(['middleware' => ['auth'], 'prefix' => 'bhavcopy_analyse', 'as' => 'bhavcopy_analyse.'], function () {
    Route::get('index', 'ControllerBhavcopyAnalyse@index')->name('index');
    Route::get('fetch_params', 'ControllerBhavcopyAnalyse@fetch_params');
    Route::get('analyse/{symbol}', 'ControllerBhavcopyAnalyse@analyse');
});

Route::group(['middleware' => ['auth'], 'prefix' => 'fno_charts', 'as' => 'fno_charts.'], function () {
    Route::get('', 'ControllerFNOCharts@index')->name('index');
    Route::get('api/config', 'ControllerFNOCharts@config');
    Route::get('api/symbols', 'ControllerFNOCharts@symbols');
    Route::get('api/search', 'ControllerFNOCharts@search');
    Route::get('api/history', 'ControllerFNOCharts@history');
});


Route::group(['middleware' => ['auth'], 'prefix' => 'nse_cm_charts', 'as' => 'nse_cm_charts.'], function () {
    Route::get('', 'ControllerNseCMCharts@index')->name('index');
    Route::get('api/config', 'ControllerNseCMCharts@config');
    Route::get('api/symbols', 'ControllerNseCMCharts@symbols');
    Route::get('api/search', 'ControllerNseCMCharts@search');
    Route::get('api/history', 'ControllerNseCMCharts@history');
});


Route::group(['middleware' => ['auth'], 'prefix' => 'process_indices', 'as' => 'process_indices.'], function () {
    Route::get('index', 'ControllerImportCSV@index')->name('index');
    Route::post('import', 'ControllerImportCSV@import')->name('import');
});

Route::get('/hire_me', function () {
    return view('hire_me');
})->name('hire_me');


Route::get('/test', function () {

    $raw = ModelBhavCopyCM::select('bhavcopy_cm.*', 'bhavcopy_delv_position.dlv_qty as dlv_qty', 'bhavcopy_delv_position.pct_dlv_traded as pct_dlv_traded')
        ->ofSymbol('AXISBANK')
        ->withDelivery()
        ->orderBy('date')->limit(30)->get();

    return response()->json([
        'status' => 1,
        'msg' => 'success',
        'data' => $raw
    ]);
});
