<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth'], 'prefix' => 'utils', 'as' => 'utils.'], function () {
    Route::get('import_csv_xlsx', 'Utils\ImportCsvXlsxUtility@index')->name('import_csv_xlsx');
    Route::post('upload_file', 'Utils\ImportCsvXlsxUtility@upload_file');

    Route::get('tables', 'Utils\CommonUtilityController@getTables');
    Route::get('columns/{table_name}', 'Utils\CommonUtilityController@getTableColumnsInfo');
    Route::get('map/{file_id}', 'Utils\ImportCsvXlsxUtility@getMap');
    Route::post('import', 'Utils\ImportCsvXlsxUtility@import');
});
