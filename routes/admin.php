<?php

use Botble\Base\Facades\BaseHelper;
use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'Whozidis\HallOfFame\Http\Controllers',
    'middleware' => ['web', 'core', 'auth'],
    'prefix' => BaseHelper::getAdminPrefix(),
], function () {
    Route::group(['prefix' => 'vulnerability-reports', 'as' => 'vulnerability-reports.'], function () {
        Route::match(['GET', 'POST'], '/', [
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

        Route::post('/bulk-action', [
            'as' => 'bulk-action',
            'uses' => 'VulnerabilityReportController@bulkAction',
            'permission' => 'vulnerability-reports.index',
        ]);

        Route::get('/parse-eml/{id}', [
            'as' => 'parse-eml',
            'uses' => 'VulnerabilityReportController@parseEml',
            'permission' => 'vulnerability-reports.edit',
        ]);

        Route::post('/save-eml-data/{id}', [
            'as' => 'save-eml-data',
            'uses' => 'VulnerabilityReportController@saveEmlData',
            'permission' => 'vulnerability-reports.edit',
        ]);
    });

    Route::group(['prefix' => 'researchers', 'as' => 'researchers.'], function () {
        Route::match(['GET', 'POST'], '/', [
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

    // Certificate management routes
    Route::group(['prefix' => 'certificates', 'as' => 'certificates.'], function () {
        Route::match(['GET', 'POST'], '/', [
            'as' => 'index',
            'uses' => 'CertificateController@index',
            'permission' => 'certificates.index',
        ]);

        Route::get('/{id}', [
            'as' => 'show',
            'uses' => 'CertificateController@show',
            'permission' => 'certificates.view',
        ]);

        Route::post('/generate/{reportId}', [
            'as' => 'generate',
            'uses' => 'CertificateController@generate',
            'permission' => 'certificates.create',
        ]);

        Route::post('/bulk-generate', [
            'as' => 'bulk-generate',
            'uses' => 'CertificateController@bulkGenerate',
            'permission' => 'certificates.create',
        ]);

        Route::post('/{id}/regenerate', [
            'as' => 'regenerate',
            'uses' => 'CertificateController@regenerate',
            'permission' => 'certificates.edit',
        ]);

        Route::delete('/{id}', [
            'as' => 'destroy',
            'uses' => 'CertificateController@destroy',
            'permission' => 'certificates.destroy',
        ]);

        Route::get('/stats', [
            'as' => 'stats',
            'uses' => 'CertificateController@stats',
            'permission' => 'certificates.index',
        ]);
    });

    // Settings routes - using dynamic admin prefix
    Route::group([
        'prefix' => 'hall-of-fame/settings',
        'middleware' => 'auth',
        'permission' => 'hall-of-fame.settings',
        'as' => 'hall-of-fame.settings.',
    ], function () {
        Route::get('pgp', [
            'as' => 'pgp.edit',
            'uses' => 'Settings\\PGPSettingController@edit',
        ]);

        Route::put('pgp', [
            'as' => 'pgp.update',
            'uses' => 'Settings\\PGPSettingController@update',
        ]);

        Route::get('researchers', [
            'as' => 'researchers.edit',
            'uses' => 'Settings\\ResearcherSettingController@edit',
        ]);

        Route::put('researchers', [
            'as' => 'researchers.update',
            'uses' => 'Settings\\ResearcherSettingController@update',
        ]);

        Route::get('vulnerability-reports', [
            'as' => 'vulnerability-reports.edit',
            'uses' => 'Settings\\VulnerabilityReportSettingController@edit',
        ]);

        Route::put('vulnerability-reports', [
            'as' => 'vulnerability-reports.update',
            'uses' => 'Settings\\VulnerabilityReportSettingController@update',
        ]);

        Route::get('certificates', [
            'as' => 'certificates.edit',
            'uses' => 'Settings\\CertificateSettingController@edit',
        ]);

        Route::put('certificates', [
            'as' => 'certificates.update',
            'uses' => 'Settings\\CertificateSettingController@update',
        ]);

        // PGP Key Management Routes
        Route::group(['prefix' => 'pgp-keys', 'as' => 'pgp-keys.'], function () {
            Route::match(['GET', 'POST'], '/', [
                'as' => 'index',
                'uses' => 'Settings\\PgpKeyController@index',
            ]);

            Route::get('/create', [
                'as' => 'create',
                'uses' => 'Settings\\PgpKeyController@create',
            ]);

            Route::post('/', [
                'as' => 'store',
                'uses' => 'Settings\\PgpKeyController@store',
            ]);

            Route::get('/{id}', [
                'as' => 'show',
                'uses' => 'Settings\\PgpKeyController@show',
            ]);

            Route::get('/{id}/activate', [
                'as' => 'activate',
                'uses' => 'Settings\\PgpKeyController@activate',
            ]);

            Route::get('/{id}/deactivate', [
                'as' => 'deactivate',
                'uses' => 'Settings\\PgpKeyController@deactivate',
            ]);

            Route::delete('/{id}', [
                'as' => 'destroy',
                'uses' => 'Settings\\PgpKeyController@destroy',
            ]);

            Route::get('/{id}/export-public', [
                'as' => 'export-public',
                'uses' => 'Settings\\PgpKeyController@exportPublic',
            ]);

            Route::post('/{id}/test-signing', [
                'as' => 'test-signing',
                'uses' => 'Settings\\PgpKeyController@testSigning',
            ]);

            Route::get('/import-provided', [
                'as' => 'import-provided',
                'uses' => 'Settings\\PgpKeyController@importProvided',
            ]);
        });
    });
});
