<?php

namespace Whozidis\HallOfFame\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Whozidis\HallOfFame\Models\Researcher;

class HallOfFameAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if researcher is authenticated via session
        if (! session('hof_researcher')) {
            return redirect()->route('public.hall-of-fame.auth.login')
                ->with('error', trans('plugins/hall-of-fame::researcher.auth_required'));
        }

        // Verify researcher still exists
        $researcher = Researcher::find(session('hof_researcher'));
        if (! $researcher) {
            session()->forget(['hof_researcher', 'hof_researcher_data']);

            return redirect()->route('public.hall-of-fame.auth.login')
                ->with('error', trans('plugins/hall-of-fame::researcher.account_not_found'));
        }

        // Make researcher available to the request
        $request->merge(['hof_researcher' => $researcher]);

        return $next($request);
    }
}
