<?php

namespace Whozidis\HallOfFame\Http\Middleware;

use Botble\Ecommerce\Models\Customer;
use Closure;
use Illuminate\Auth\SessionGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Whozidis\HallOfFame\Notifications\PasswordChangedNotification;

class AfterPasswordChangeActions
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        try {
            $guard = Auth::guard('customer');
            /** @var Customer|null $user */
            $user = $guard?->user();

            // If password field present, we consider a change attempt; if success, invalidate other sessions
            if ($user && $request->filled('password')) {
                // logoutOtherDevices requires the new plain-text password to rotate all other sessions
                if ($guard instanceof SessionGuard) {
                    $guard->logoutOtherDevices((string) $request->input('password'));
                }

                // Notify user of password change
                try {
                    $user->notify(new PasswordChangedNotification($request->ip(), $request->userAgent()));
                } catch (\Throwable $e) {
                    Log::warning('Failed sending PasswordChangedNotification: ' . $e->getMessage());
                }
            }
    } catch (\Throwable $e) {
            Log::warning('AfterPasswordChangeActions middleware error: ' . $e->getMessage());
        }

        return $response;
    }
}
