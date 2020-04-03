<?php


use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['auth'], 'prefix' => 'fetch', 'as' => 'fetch.'], function () {
    Route::get('app_info', 'CommonFetchController@app_info');
    Route::get('fno_stocks/{type}', 'CommonFetchController@fno_stocks');
    Route::get('cm_stocks/{type}', 'CommonFetchController@cm_stocks');
    Route::get('favorites_stocks/{type}', 'CommonFetchController@favorites_stocks');
    Route::post('toggle_favorite', 'CommonFetchController@toggle_favorite');
});


