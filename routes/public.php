<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'Whozidis\HallOfFame\Http\Controllers',
    'middleware' => ['web', 'core'],
], function () {
    Route::prefix('hall-of-fame')->group(function () {
        Route::get('/', [
            'uses' => 'PublicVulnerabilityReportController@index',
            'as' => 'public.hall-of-fame',
        ]);

        Route::prefix('vulnerability-reports')->as('public.vulnerability-reports.')->group(function () {
            Route::get('/', [
                'uses' => 'PublicVulnerabilityReportController@index',
                'as' => 'index',
            ]);

            Route::get('/{id}', [
                'uses' => 'PublicVulnerabilityReportController@show',
                'as' => 'show',
            ])->where('id', '[0-9]+');

            Route::middleware(['auth'])->group(function () {
                Route::get('/create', [
                    'uses' => 'PublicVulnerabilityReportController@create',
                    'as' => 'create',
                ]);

                Route::post('/store', [
                    'uses' => 'PublicVulnerabilityReportController@store',
                    'as' => 'store',
                ]);

                Route::get('/my-reports', [
                    'uses' => 'PublicVulnerabilityReportController@myReports',
                    'as' => 'my-reports',
                ]);
            });
        });
    });
});
