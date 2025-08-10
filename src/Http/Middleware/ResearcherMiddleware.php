<?php

namespace Whozidis\HallOfFame\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Whozidis\HallOfFame\Models\Researcher;

class ResearcherMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $researcherId = session('researcher_id');

        if (!$researcherId) {
            return redirect()->route('researchers.login')
                ->with('error', trans('plugins/hall-of-fame::researcher.login_required'));
        }

        $researcher = Researcher::find($researcherId);

        if (!$researcher) {
            session()->forget('researcher_id');
            return redirect()->route('researchers.login')
                ->with('error', trans('plugins/hall-of-fame::researcher.invalid_researcher'));
        }

        $request->merge(['researcher' => $researcher]);

        return $next($request);
    }
}
