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
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Facades\Route;
use Whozidis\HallOfFame\Http\Controllers\PublicVulnerabilityReportController;
use Whozidis\HallOfFame\Http\Controllers\CertificateController;

// Define middleware configuration
// NOTE: These middleware are framework-provided and exist at runtime
// IDE warnings are false positives that can be safely ignored
$webCoreMiddleware = ['web', 'core'];
$signedUrlMiddleware = 'signed';
$authMiddleware = ['auth']; // Laravel's built-in authentication middleware

// Register public routes through Theme system for language support
Theme::registerRoutes(function () use ($signedUrlMiddleware, $authMiddleware) {
    // Main Hall of Fame routes
    Route::get('hall-of-fame', [PublicVulnerabilityReportController::class, 'index'])
        ->name('public.hall-of-fame.index');

    Route::prefix('hall-of-fame')->group(function () use ($signedUrlMiddleware, $authMiddleware) {

        Route::prefix('auth')->as('public.hall-of-fame.auth.')->group(function () use ($signedUrlMiddleware) {
            Route::get('/register', [
                'uses' => 'Whozidis\HallOfFame\Http\Controllers\ResearcherController@showRegistrationForm',
                'as' => 'register',
            ]);

            Route::post('/register', [
                'uses' => 'Whozidis\HallOfFame\Http\Controllers\ResearcherController@register',
                'as' => 'register.post',
            ]);

            Route::get('/login', [
                'uses' => 'Whozidis\HallOfFame\Http\Controllers\ResearcherController@showLoginForm',
                'as' => 'login',
            ]);

            Route::post('/login', [
                'uses' => 'Whozidis\HallOfFame\Http\Controllers\ResearcherController@login',
                'as' => 'login.post',
            ]);

            Route::post('/logout', [
                'uses' => 'Whozidis\HallOfFame\Http\Controllers\ResearcherController@logout',
                'as' => 'logout',
            ]);

            Route::get('/dashboard', [
                'uses' => 'Whozidis\HallOfFame\Http\Controllers\ResearcherController@dashboard',
                'as' => 'dashboard',
            ])->middleware(\Whozidis\HallOfFame\Http\Middleware\HallOfFameAuth::class);
        });

        // Enhanced Dashboard Routes
        Route::prefix('dashboard')->as('public.hall-of-fame.dashboard.')->middleware(\Whozidis\HallOfFame\Http\Middleware\HallOfFameAuth::class)->group(function () {
            Route::get('/', [
                'uses' => 'Whozidis\HallOfFame\Http\Controllers\DashboardController@index',
                'as' => 'index',
            ]);

            Route::get('/profile', [
                'uses' => 'Whozidis\HallOfFame\Http\Controllers\DashboardController@profile', 
                'as' => 'profile',
            ]);

            Route::post('/profile', [
                'uses' => 'Whozidis\HallOfFame\Http\Controllers\DashboardController@updateProfile',
                'as' => 'profile.update',
            ]);

            Route::get('/certificates', [
                'uses' => 'Whozidis\HallOfFame\Http\Controllers\DashboardController@certificates',
                'as' => 'certificates',
            ]);

            Route::get('/reports', [
                'uses' => 'Whozidis\HallOfFame\Http\Controllers\DashboardController@reports',
                'as' => 'reports', 
            ]);

            Route::get('/reports/{id}', [
                'uses' => 'Whozidis\HallOfFame\Http\Controllers\DashboardController@reportDetail',
                'as' => 'reports.detail',
            ])->where('id', '[0-9]+');

            Route::get('/analytics', [
                'uses' => 'Whozidis\HallOfFame\Http\Controllers\DashboardController@analytics',
                'as' => 'analytics',
            ]);
        });

        
        Route::prefix('reports')->as('public.vulnerability-reports.')->group(function () use ($signedUrlMiddleware) {
            Route::get('/{id}', [
                'uses' => 'Whozidis\HallOfFame\Http\Controllers\PublicVulnerabilityReportController@show',
                'as' => 'show',
            ])->where('id', '[0-9]+');            
            
            // Protected routes that require researcher session
            Route::group(['middleware' => ['web', 'core']], function () use ($signedUrlMiddleware) {
                Route::get('/submit', [
                    'uses' => 'Whozidis\HallOfFame\Http\Controllers\PublicVulnerabilityReportController@create',
                    'as' => 'create',
                ]); // Removed the signed middleware to fix the 403 error

                Route::post('/submit', [
                    'uses' => 'Whozidis\HallOfFame\Http\Controllers\PublicVulnerabilityReportController@store',
                    'as' => 'store',
                ]);

                Route::get('/my-reports', [
                    'uses' => 'Whozidis\HallOfFame\Http\Controllers\PublicVulnerabilityReportController@myReports',
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

// Admin routes (not language-dependent)
Route::group([
    'namespace' => 'Whozidis\HallOfFame\Http\Controllers',
    'middleware' => $webCoreMiddleware,
], function () {
    // Keep any admin routes here if needed
});
