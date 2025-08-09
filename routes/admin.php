<?php

use Botble\Base\Facades\BaseHelper;
use Illuminate\Support\Facades\Route;
use Whozidis\HallOfFame\Http\Controllers\VulnerabilityReportController;

Route::group(['namespace' => 'Whozidis\HallOfFame\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'vulnerability-reports', 'as' => 'vulnerability-reports.'], function () {
            Route::resource('', 'VulnerabilityReportController')->parameters(['' => 'vulnerability-report']);
        });
    });
});
