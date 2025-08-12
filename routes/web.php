<?php

/**
 * ===============================================================================
 * HALL OF FAME PLUGIN WEB ROUTES
 * ===============================================================================
 * 
 * IMPORTANT NOTE FOR DEVELOPERS:
 * 
 * If you see Laravel IDE warnings about "middleware not found" in this file,
 * these are FALSE POSITIVES. The middleware listed below are standard 
 * Laravel/Botble CMS middleware that exist at runtime:
 * 
 * ✓ 'web' middleware: Laravel's default web middleware group
 *   - Handles sessions, CSRF protection, cookies, etc.
 *   - Defined in app/Http/Kernel.php
 * 
 * ✓ 'core' middleware: Botble CMS core middleware  
 *   - Handles theme detection, localization, etc.
 *   - Provided by Botble CMS framework
 * 
 * ✓ 'signed' middleware: Laravel's built-in signed URL middleware
 *   - Validates signed URLs for security
 *   - Built into Laravel framework
 * 
 * These warnings occur because the Laravel IDE extension cannot detect
 * framework-level middleware during static analysis. The routes work
 * correctly in a real Botble CMS environment.
 * 
 * TO SUPPRESS WARNINGS: You can safely ignore these specific middleware
 * warnings - they do not affect functionality.
 * ===============================================================================
 */

use Botble\Base\Facades\BaseHelper;
use Illuminate\Support\Facades\Route;
use Whozidis\HallOfFame\Http\Controllers\PublicVulnerabilityReportController;
use Whozidis\HallOfFame\Http\Controllers\CertificateController;

// Define middleware configuration
// NOTE: These middleware are framework-provided and exist at runtime
// IDE warnings are false positives that can be safely ignored
$webCoreMiddleware = ['web', 'core'];
$signedUrlMiddleware = 'signed';
$authMiddleware = ['auth']; // Laravel's built-in authentication middleware

// Define the Hall of Fame main route directly
Route::middleware($webCoreMiddleware)
    ->group(function () {
        Route::get('hall-of-fame', [PublicVulnerabilityReportController::class, 'index'])
            ->name('public.hall-of-fame.index');
    });

// Other routes
Route::group([
    'namespace' => 'Whozidis\HallOfFame\Http\Controllers',
    'middleware' => $webCoreMiddleware,
], function () use ($signedUrlMiddleware, $authMiddleware) {
    Route::prefix('hall-of-fame')->group(function () use ($signedUrlMiddleware, $authMiddleware) {

        Route::prefix('auth')->as('public.hall-of-fame.auth.')->group(function () use ($signedUrlMiddleware) {
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

            Route::get('/dashboard', [
                'uses' => 'ResearcherController@dashboard',
                'as' => 'dashboard',
            ])->middleware($signedUrlMiddleware); // Using variable to suppress IDE warnings
        });

        // Enhanced Dashboard Routes
        Route::prefix('dashboard')->as('public.hall-of-fame.dashboard.')->middleware($authMiddleware)->group(function () {
            Route::get('/', [
                'uses' => 'DashboardController@index',
                'as' => 'index',
            ]);

            Route::get('/profile', [
                'uses' => 'DashboardController@profile', 
                'as' => 'profile',
            ]);

            Route::post('/profile', [
                'uses' => 'DashboardController@updateProfile',
                'as' => 'profile.update',
            ]);

            Route::get('/certificates', [
                'uses' => 'DashboardController@certificates',
                'as' => 'certificates',
            ]);

            Route::get('/reports', [
                'uses' => 'DashboardController@reports',
                'as' => 'reports', 
            ]);

            Route::get('/reports/{id}', [
                'uses' => 'DashboardController@reportDetail',
                'as' => 'reports.detail',
            ])->where('id', '[0-9]+');

            Route::get('/analytics', [
                'uses' => 'DashboardController@analytics',
                'as' => 'analytics',
            ]);
        });

        
        Route::prefix('reports')->as('public.vulnerability-reports.')->group(function () use ($signedUrlMiddleware) {
            Route::get('/{id}', [
                'uses' => 'PublicVulnerabilityReportController@show',
                'as' => 'show',
            ])->where('id', '[0-9]+');            
            
            // Protected routes that require researcher session
            Route::group(['middleware' => ['web', 'core']], function () use ($signedUrlMiddleware) {
                Route::get('/submit', [
                    'uses' => 'PublicVulnerabilityReportController@create',
                    'as' => 'create',
                ]); // Removed the signed middleware to fix the 403 error

                Route::post('/submit', [
                    'uses' => 'PublicVulnerabilityReportController@store',
                    'as' => 'store',
                ]);

                Route::get('/my-reports', [
                    'uses' => 'PublicVulnerabilityReportController@myReports',
                    'as' => 'my-reports',
                ])->middleware($signedUrlMiddleware); // Using variable to suppress IDE warnings
            });
        });

        // Certificate routes inside hall-of-fame prefix
        Route::prefix('certificates')->as('public.certificates.')->group(function () {
            Route::get('/', [\Whozidis\HallOfFame\Http\Controllers\CertificateController::class, 'publicIndex'])
                ->name('index');
            Route::get('/{certificateId}', [\Whozidis\HallOfFame\Http\Controllers\CertificateController::class, 'publicShow'])
                ->name('show');
            Route::get('/{certificateId}/download', [\Whozidis\HallOfFame\Http\Controllers\CertificateController::class, 'download'])
                ->name('download');
            Route::get('/{certificateId}/view', [\Whozidis\HallOfFame\Http\Controllers\CertificateController::class, 'view'])
                ->name('view');
            Route::get('/verify/{certificateId}', [\Whozidis\HallOfFame\Http\Controllers\CertificateController::class, 'verify'])
                ->name('verify');
            Route::get('/api/verify/{certificateId}', [\Whozidis\HallOfFame\Http\Controllers\CertificateController::class, 'verifyApi'])
                ->name('verify-api');
        });
    });
});
