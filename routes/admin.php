<?php

use Botble\Base\Facades\BaseHelper;
use Illuminate\Support\Facades\Route;
use Whozidis\HallOfFame\Http\Controllers\VulnerabilityReportController;
use Whozidis\HallOfFame\Http\Controllers\ResearcherController;

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

        Route::get('/create', [
            'as' => 'create',
            'uses' => 'VulnerabilityReportController@create',
            'permission' => 'vulnerability-reports.create',
        ]);

        Route::post('/store', [
            'as' => 'store',
            'uses' => 'VulnerabilityReportController@store',
            'permission' => 'vulnerability-reports.create',
        ]);

        Route::get('/edit/{id}', [
            'as' => 'edit',
            'uses' => 'VulnerabilityReportController@edit',
            'permission' => 'vulnerability-reports.edit',
        ]);

        Route::put('/edit/{id}', [
            'as' => 'update',
            'uses' => 'VulnerabilityReportController@update',
            'permission' => 'vulnerability-reports.edit',
        ]);

        Route::delete('/{id}', [
            'as' => 'destroy',
            'uses' => 'VulnerabilityReportController@destroy',
            'permission' => 'vulnerability-reports.destroy',
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

    Route::group(['prefix' => 'researchers', 'as' => 'researchers.'], function () {
        Route::get('/', [
            'as' => 'index',
            'uses' => 'ResearcherController@index',
            'permission' => 'researchers.index',
        ]);

        Route::get('/create', [
            'as' => 'create',
            'uses' => 'ResearcherController@create',
            'permission' => 'researchers.create',
        ]);

        Route::post('/store', [
            'as' => 'store',
            'uses' => 'ResearcherController@store',
            'permission' => 'researchers.create',
        ]);

        Route::get('/edit/{id}', [
            'as' => 'edit',
            'uses' => 'ResearcherController@edit',
            'permission' => 'researchers.edit',
        ]);

        Route::put('/edit/{id}', [
            'as' => 'update',
            'uses' => 'ResearcherController@update',
            'permission' => 'researchers.edit',
        ]);

        Route::delete('/{id}', [
            'as' => 'destroy',
            'uses' => 'ResearcherController@destroy',
            'permission' => 'researchers.destroy',
        ]);
    });
});
