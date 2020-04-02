<?php


use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['auth'], 'prefix' => 'fetch', 'as' => 'fetch.'], function () {
    Route::get('app_info', 'CommonFetchController@app_info');
    Route::get('fno_stocks', 'CommonFetchController@fno_stocks');
    Route::get('favorites_stocks', 'CommonFetchController@favorites_stocks');
    Route::post('toggle_favorite', 'CommonFetchController@toggle_favorite');
});


