<?php

namespace Whozidis\HallOfFame\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Whozidis\HallOfFame\Models\VulnerabilityReport;
use Whozidis\HallOfFame\Models\Certificate;
use Carbon\Carbon;

class DashboardController extends BaseController
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Set theme layout and breadcrumbs
        \Botble\Theme\Facades\Theme::setLayout('hall-of-fame');
        \Botble\Theme\Facades\Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add('Hall of Fame', route('public.hall-of-fame.index'))
            ->add('Dashboard', route('public.hall-of-fame.dashboard.index'));
        
        // Get user's reports with statistics
        $reports = VulnerabilityReport::where('user_id', $user->id)
            ->latest()
            ->paginate(5);

        // Dashboard statistics
        $stats = [
            'total_reports' => VulnerabilityReport::where('user_id', $user->id)->count(),
            'published_reports' => VulnerabilityReport::where('user_id', $user->id)->where('status', 'published')->count(),
            'pending_reports' => VulnerabilityReport::where('user_id', $user->id)->where('status', 'pending')->count(),
            'certificates' => Certificate::whereHas('vulnerabilityReport', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count(),
        ];

        // Recent activity (last 30 days)
        $recentActivity = VulnerabilityReport::where('user_id', $user->id)
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Vulnerability type breakdown
        $vulnerabilityTypes = VulnerabilityReport::where('user_id', $user->id)
            ->selectRaw('vulnerability_type, count(*) as count')
            ->groupBy('vulnerability_type')
            ->pluck('count', 'vulnerability_type')
            ->toArray();

        // Monthly report count for chart
        $monthlyReports = VulnerabilityReport::where('user_id', $user->id)
            ->where('created_at', '>=', Carbon::now()->subYear())
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, count(*) as count')
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(function($item) {
                return [
                    'date' => Carbon::createFromDate($item->year, $item->month, 1)->format('M Y'),
                    'count' => $item->count
                ];
            });

        // User certificates
        $certificates = Certificate::whereHas('vulnerabilityReport', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->latest()->limit(3)->get();

        // Calculate user rank (based on published reports)
        $userRank = VulnerabilityReport::selectRaw('user_id, count(*) as report_count')
            ->where('status', 'published')
            ->groupBy('user_id')
            ->orderBy('report_count', 'desc')
            ->get()
            ->pluck('user_id')
            ->search($user->id);
        
        $userRank = $userRank !== false ? $userRank + 1 : null;

        return \Botble\Theme\Facades\Theme::of('plugins/hall-of-fame::dashboard.index', compact(
            'user', 'reports', 'stats', 'recentActivity', 'vulnerabilityTypes', 
            'monthlyReports', 'certificates', 'userRank'
        ))->render();
    }

    public function profile()
    {
        $user = Auth::user();

        // Set theme layout and breadcrumbs
        \Botble\Theme\Facades\Theme::setLayout('hall-of-fame');
        \Botble\Theme\Facades\Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add('Hall of Fame', route('public.hall-of-fame.index'))
            ->add('Dashboard', route('public.hall-of-fame.dashboard.index'))
            ->add('Profile', route('public.hall-of-fame.dashboard.profile'));
        
        return \Botble\Theme\Facades\Theme::of('plugins/hall-of-fame::dashboard.profile', compact('user'))->render();
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:1000',
            'website' => 'nullable|url|max:255',
            'twitter' => 'nullable|string|max:100',
            'github' => 'nullable|string|max:100',
            'linkedin' => 'nullable|string|max:100',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update or create researcher profile if it exists
        if (class_exists('\Whozidis\HallOfFame\Models\Researcher')) {
            $researcher = \Whozidis\HallOfFame\Models\Researcher::where('user_id', $user->id)->first();
            if ($researcher) {
                $researcher->update([
                    'bio' => $request->bio,
                    'website' => $request->website,
                    'twitter' => $request->twitter,
                    'github' => $request->github,
                    'linkedin' => $request->linkedin,
                ]);
            }
        }

        return redirect()->route('public.hall-of-fame.dashboard.profile')
            ->with('success', trans('plugins/hall-of-fame::dashboard.profile_updated'));
    }

    public function certificates(Request $request)
    {
        $user = Auth::user();

        // Set theme layout and breadcrumbs
        \Botble\Theme\Facades\Theme::setLayout('hall-of-fame');
        \Botble\Theme\Facades\Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add('Hall of Fame', route('public.hall-of-fame.index'))
            ->add('Dashboard', route('public.hall-of-fame.dashboard.index'))
            ->add('Certificates', route('public.hall-of-fame.dashboard.certificates'));
        
        $certificates = Certificate::whereHas('vulnerabilityReport', function($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with('vulnerabilityReport')->latest()->paginate(10);

        return \Botble\Theme\Facades\Theme::of('plugins/hall-of-fame::dashboard.certificates', compact('certificates'))->render();
    }

    public function reports(Request $request)
    {
        $user = Auth::user();

        // Set theme layout and breadcrumbs
        \Botble\Theme\Facades\Theme::setLayout('hall-of-fame');
        \Botble\Theme\Facades\Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add('Hall of Fame', route('public.hall-of-fame.index'))
            ->add('Dashboard', route('public.hall-of-fame.dashboard.index'))
            ->add('My Reports', route('public.hall-of-fame.dashboard.reports'));
        
        $reports = VulnerabilityReport::where('user_id', $user->id)
            ->with(['attachments', 'certificate'])
            ->latest()
            ->paginate(10);

        return \Botble\Theme\Facades\Theme::of('plugins/hall-of-fame::dashboard.reports', compact('reports'))->render();
    }

    public function reportDetail(Request $request, $id)
    {
        $user = Auth::user();

        // Set theme layout and breadcrumbs
        \Botble\Theme\Facades\Theme::setLayout('hall-of-fame');
        \Botble\Theme\Facades\Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add('Hall of Fame', route('public.hall-of-fame.index'))
            ->add('Dashboard', route('public.hall-of-fame.dashboard.index'))
            ->add('My Reports', route('public.hall-of-fame.dashboard.reports'))
            ->add('Report Details', '');
        
        $report = VulnerabilityReport::where('user_id', $user->id)
            ->where('id', $id)
            ->with(['attachments', 'certificate'])
            ->firstOrFail();

        return \Botble\Theme\Facades\Theme::of('plugins/hall-of-fame::dashboard.report-detail', compact('report'))->render();
    }

    public function analytics(Request $request)
    {
        $user = Auth::user();

        // Set theme layout and breadcrumbs
        \Botble\Theme\Facades\Theme::setLayout('hall-of-fame');
        \Botble\Theme\Facades\Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add('Hall of Fame', route('public.hall-of-fame.index'))
            ->add('Dashboard', route('public.hall-of-fame.dashboard.index'))
            ->add('Analytics', route('public.hall-of-fame.dashboard.analytics'));
        
        // Comprehensive analytics data
        $analytics = [
            'total_reports' => VulnerabilityReport::where('user_id', $user->id)->count(),
            'published_reports' => VulnerabilityReport::where('user_id', $user->id)->where('status', 'published')->count(),
            'pending_reports' => VulnerabilityReport::where('user_id', $user->id)->where('status', 'pending')->count(),
            'rejected_reports' => VulnerabilityReport::where('user_id', $user->id)->where('status', 'rejected')->count(),
            'certificates_earned' => Certificate::whereHas('vulnerabilityReport', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })->count(),
        ];

        // Success rate
        $analytics['success_rate'] = $analytics['total_reports'] > 0 
            ? round(($analytics['published_reports'] / $analytics['total_reports']) * 100, 1) 
            : 0;

        // Vulnerability types distribution
        $vulnerabilityTypes = VulnerabilityReport::where('user_id', $user->id)
            ->selectRaw('vulnerability_type, count(*) as count')
            ->groupBy('vulnerability_type')
            ->orderBy('count', 'desc')
            ->get();

        // Monthly submissions over the last year
        $monthlyData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = VulnerabilityReport::where('user_id', $user->id)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            
            $monthlyData[] = [
                'month' => $date->format('M Y'),
                'count' => $count
            ];
        }

        // Status timeline
        $statusTimeline = VulnerabilityReport::where('user_id', $user->id)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get();

        return \Botble\Theme\Facades\Theme::of('plugins/hall-of-fame::dashboard.analytics', compact(
            'analytics', 'vulnerabilityTypes', 'monthlyData', 'statusTimeline'
        ))->render();
    }
}
