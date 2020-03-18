<?php


use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['auth'], 'prefix' => 'fetch', 'as' => 'fetch.'], function () {
    Route::get('fno_stocks', 'CommonFetchController@fno_stocks');
});


