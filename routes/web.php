<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Whozidis\HallOfFame\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::group(['as' => 'public.'], function () {
        Route::prefix('hall-of-fame')->group(function () {
            Route::get('/', [
                'as' => 'hall-of-fame',
                'uses' => 'PublicVulnerabilityReportController@hall',
            ]);

            Route::prefix('vulnerability-reports')->as('vulnerability-reports.')->middleware(['core', 'web'])->group(function () {
                Route::get('/', [
                    'as' => 'index',
                    'uses' => 'PublicVulnerabilityReportController@index',
                ]);

                Route::get('/create', [
                    'as' => 'create',
                    'uses' => 'PublicVulnerabilityReportController@create',
                ]);

                Route::post('/store', [
                    'as' => 'store',
                    'uses' => 'PublicVulnerabilityReportController@store',
                ]);

                Route::get('/{id}', [
                    'as' => 'show',
                    'uses' => 'PublicVulnerabilityReportController@show',
                ])->where('id', '[0-9]+');
            });
        });
    });
});
