<?php

use Botble\Base\Facades\BaseHelper;
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

        Route::prefix('researchers')->as('researchers.')->group(function () {
            Route::get('/register', [
                'uses' => 'ResearcherController@showRegistrationForm',
                'as' => 'register',
            ]);
    
            Route::post('/register', [
                'uses' => 'ResearcherController@register',
                'as' => 'register.post',
            ]);
    
            Route::get('/login', [
                'uses' => 'ResearcherController@showLoginForm',
                'as' => 'login',
            ]);
    
            Route::post('/login', [
                'uses' => 'ResearcherController@login',
                'as' => 'login.post',
            ]);
    
            Route::post('/logout', [
                'uses' => 'ResearcherController@logout',
                'as' => 'logout',
            ]);
        });

        Route::prefix('reports')->as('public.vulnerability-reports.')->group(function () {
            Route::get('/', [
                'uses' => 'PublicVulnerabilityReportController@index',
                'as' => 'index',
            ]);

            Route::get('/{id}', [
                'uses' => 'PublicVulnerabilityReportController@show',
                'as' => 'show',
            ])->where('id', '[0-9]+');

            // Protected routes that require researcher session
            Route::group(['middleware' => ['web', 'core'], 'prefix' => 'auth'], function () {
                Route::get('/submit', [
                    'uses' => 'PublicVulnerabilityReportController@create',
                    'as' => 'create',
                ])->middleware('signed');

                Route::post('/submit', [
                    'uses' => 'PublicVulnerabilityReportController@store',
                    'as' => 'store',
                ]);

                Route::get('/my-reports', [
                    'uses' => 'PublicVulnerabilityReportController@myReports',
                    'as' => 'my-reports',
                ])->middleware('signed');
            });
        });
    });
});
