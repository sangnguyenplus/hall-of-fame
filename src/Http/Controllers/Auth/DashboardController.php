<?php

namespace Whozidis\HallOfFame\Http\Controllers\Auth;

use Botble\Base\Facades\PageTitle;
use Botble\Base\Http\Controllers\BaseController;
use Botble\SeoHelper\Facades\SeoHelper;
use Illuminate\Http\Request;
use Whozidis\HallOfFame\Models\VulnerabilityReport;

class DashboardController extends BaseController
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Get reports created by this admin user OR reports where researcher_email matches
        $reports = VulnerabilityReport::where(function($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->orWhere('researcher_email', $user->email);
        })
        ->latest()
        ->paginate(10);

        return view('plugins/hall-of-fame::auth.dashboard', compact('reports'));
    }
}
