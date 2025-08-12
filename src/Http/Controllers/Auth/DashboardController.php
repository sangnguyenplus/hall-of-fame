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

        $reports = VulnerabilityReport::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('plugins/hall-of-fame::auth.dashboard', compact('reports'));
    }
}
