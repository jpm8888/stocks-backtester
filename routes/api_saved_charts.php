<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['api'], 'prefix' => 'chart_save_engine/v1', 'as' => 'chart_save_engine.'], function () {
    Route::get('charts', 'ControllerChartSaveEngine@index');
    Route::post('charts', 'ControllerChartSaveEngine@store');
    Route::delete('charts', 'ControllerChartSaveEngine@destroy');
});
