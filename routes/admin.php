<?php

use Botble\Base\Facades\BaseHelper;
use Illuminate\Support\Facades\Route;
use Whozidis\HallOfFame\Http\Controllers\VulnerabilityReportController;

Route::group([
    'namespace' => 'Whozidis\HallOfFame\Http\Controllers',
    'middleware' => ['web', 'core', 'auth', 'auth.checker'],
    'prefix' => BaseHelper::getAdminPrefix(),
], function () {
    Route::group(['prefix' => 'vulnerability-reports', 'as' => 'vulnerability-reports.'], function () {
        Route::get('/', [
            'as' => 'index',
            'uses' => 'VulnerabilityReportController@index',
            'permission' => 'vulnerability-reports.index',
        ]);

        Route::get('/edit/{id}', [
            'as' => 'edit',
            'uses' => 'VulnerabilityReportController@edit',
            'permission' => 'vulnerability-reports.edit',
        ]);

        Route::put('/update/{id}', [
            'as' => 'update',
            'uses' => 'VulnerabilityReportController@update',
            'permission' => 'vulnerability-reports.edit',
        ]);

        Route::post('/approve/{id}', [
            'as' => 'approve',
            'uses' => 'VulnerabilityReportController@approve',
            'permission' => 'vulnerability-reports.approve',
        ]);

        Route::post('/reject/{id}', [
            'as' => 'reject',
            'uses' => 'VulnerabilityReportController@reject',
            'permission' => 'vulnerability-reports.reject',
        ]);
    });
});
