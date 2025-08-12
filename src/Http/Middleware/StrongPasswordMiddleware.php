<?php

namespace Whozidis\HallOfFame\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StrongPasswordMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $password = (string) $request->input('password');

        if ($password !== '') {
            // Baseline: length >= 10 and mix of classes
            $minLen = 10;
            $hasUpper = (bool) preg_match('/[A-Z]/u', $password);
            $hasLower = (bool) preg_match('/[a-z]/u', $password);
            $hasDigit = (bool) preg_match('/\d/u', $password);
            $hasSymbol = (bool) preg_match('/[^\p{L}\p{Nd}]/u', $password);

            $classCount = ($hasUpper ? 1 : 0) + ($hasLower ? 1 : 0) + ($hasDigit ? 1 : 0) + ($hasSymbol ? 1 : 0);

            if (Str::length($password) < $minLen || $classCount < 3) {
                return back()->withErrors([
                    'password' => __('Password must be at least :min characters and include at least 3 of: uppercase, lowercase, number, symbol.', ['min' => $minLen]),
                ])->withInput();
            }

            // Block common weak passwords
            $weakList = [
                'password', '123456', '123456789', 'qwerty', '111111', 'abc123', '123123', 'test123', 'test', 'whozidis',
            ];
            if (in_array(mb_strtolower($password), $weakList, true)) {
                return back()->withErrors([
                    'password' => __('This password is too common. Please choose a stronger one.'),
                ])->withInput();
            }
        }

        return $next($request);
    }
}
