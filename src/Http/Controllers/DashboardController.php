<?php

namespace Whozidis\HallOfFame\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Whozidis\HallOfFame\Models\VulnerabilityReport;
use Whozidis\HallOfFame\Models\Certificate;
use Carbon\Carbon;

class DashboardController extends BaseController
{
    public function index(Request $request)
    {
        $researcher = $request->hof_researcher;

        // Set theme layout and breadcrumbs
        \Botble\Theme\Facades\Theme::setLayout('hall-of-fame');
        \Botble\Theme\Facades\Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(trans('plugins/hall-of-fame::vulnerability-reports.hall_of_fame'), route('public.hall-of-fame.index'))
            ->add(trans('plugins/hall-of-fame::dashboard.dashboard'), route('public.hall-of-fame.dashboard.index'));
        
        // Get researcher's reports with statistics
        $reports = VulnerabilityReport::where('researcher_email', $researcher->email)
            ->latest()
            ->paginate(5);

        // Dashboard statistics
        $stats = [
            'total_reports' => VulnerabilityReport::where('researcher_email', $researcher->email)->count(),
            'published_reports' => VulnerabilityReport::where('researcher_email', $researcher->email)->where('status', 'published')->count(),
            'pending_reports' => VulnerabilityReport::where('researcher_email', $researcher->email)->where('status', 'pending')->count(),
            'certificates' => Certificate::whereHas('vulnerabilityReport', function($q) use ($researcher) {
                $q->where('researcher_email', $researcher->email);
            })->count(),
        ];

        // Recent activity (last 30 days)
        $recentActivity = VulnerabilityReport::where('researcher_email', $researcher->email)
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Vulnerability type breakdown
        $vulnerabilityTypes = VulnerabilityReport::where('researcher_email', $researcher->email)
            ->selectRaw('vulnerability_type, count(*) as count')
            ->groupBy('vulnerability_type')
            ->pluck('count', 'vulnerability_type')
            ->toArray();

        // Monthly report count for chart
        $monthlyReports = VulnerabilityReport::where('researcher_email', $researcher->email)
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
        $certificates = Certificate::whereHas('vulnerabilityReport', function($q) use ($researcher) {
            $q->where('researcher_email', $researcher->email);
        })->latest()->limit(3)->get();

        // Calculate researcher rank (based on published reports)
        $researcherRank = VulnerabilityReport::selectRaw('researcher_email, count(*) as report_count')
            ->where('status', 'published')
            ->groupBy('researcher_email')
            ->orderBy('report_count', 'desc')
            ->get()
            ->pluck('researcher_email')
            ->search($researcher->email);
        
        $researcherRank = $researcherRank !== false ? $researcherRank + 1 : null;

        return \Botble\Theme\Facades\Theme::of('plugins/hall-of-fame::dashboard.index', compact(
            'researcher', 'reports', 'stats', 'recentActivity', 'vulnerabilityTypes', 
            'monthlyReports', 'certificates', 'researcherRank'
        ))->render();
    }

    public function profile(Request $request)
    {
        $researcher = $request->hof_researcher;
        $user = $researcher; // Make user variable available for the view

        // Set theme layout and breadcrumbs
        \Botble\Theme\Facades\Theme::setLayout('hall-of-fame');
        \Botble\Theme\Facades\Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(trans('plugins/hall-of-fame::vulnerability-reports.hall_of_fame'), route('public.hall-of-fame.index'))
            ->add(trans('plugins/hall-of-fame::dashboard.dashboard'), route('public.hall-of-fame.dashboard.index'))
            ->add(trans('plugins/hall-of-fame::dashboard.profile'), route('public.hall-of-fame.dashboard.profile'));
        
        return \Botble\Theme\Facades\Theme::of('plugins/hall-of-fame::dashboard.profile', compact('user', 'researcher'))->render();
    }

    public function updateProfile(Request $request)
    {
        $researcher = $request->hof_researcher;
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:hof_researchers,email,' . $researcher->id,
            'bio' => 'nullable|string|max:1000',
            'website' => 'nullable|url|max:255',
            'twitter' => 'nullable|string|max:100',
            'github' => 'nullable|string|max:100',
        ]);

        $researcher->update([
            'name' => $request->name,
            'email' => $request->email,
            'bio' => $request->bio,
            'website' => $request->website,
            'twitter' => $request->twitter,
            'github' => $request->github,
        ]);

        // Update session data
        session(['hof_researcher_data' => $researcher->fresh()->toArray()]);

        return redirect()->route('public.hall-of-fame.dashboard.profile')
            ->with('success', trans('plugins/hall-of-fame::dashboard.profile_updated'));
    }

    public function certificates(Request $request)
    {
        $researcher = $request->hof_researcher;

        // Set theme layout and breadcrumbs
        \Botble\Theme\Facades\Theme::setLayout('hall-of-fame');
        \Botble\Theme\Facades\Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(trans('plugins/hall-of-fame::vulnerability-reports.hall_of_fame'), route('public.hall-of-fame.index'))
            ->add('Dashboard', route('public.hall-of-fame.dashboard.index'))
            ->add('Certificates', route('public.hall-of-fame.dashboard.certificates'));
        
        $certificates = Certificate::whereHas('vulnerabilityReport', function($q) use ($researcher) {
            $q->where('researcher_email', $researcher->email);
        })->with('vulnerabilityReport')->latest()->paginate(10);

        return \Botble\Theme\Facades\Theme::of('plugins/hall-of-fame::dashboard.certificates', compact('certificates', 'researcher'))->render();
    }

    public function reports(Request $request)
    {
        $researcher = $request->hof_researcher;

        // Set theme layout and breadcrumbs
        \Botble\Theme\Facades\Theme::setLayout('hall-of-fame');
        \Botble\Theme\Facades\Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(trans('plugins/hall-of-fame::vulnerability-reports.hall_of_fame'), route('public.hall-of-fame.index'))
            ->add('Dashboard', route('public.hall-of-fame.dashboard.index'))
            ->add('My Reports', route('public.hall-of-fame.dashboard.reports'));
        
        $reports = VulnerabilityReport::where('researcher_email', $researcher->email)
            ->with(['attachments', 'certificate'])
            ->latest()
            ->paginate(10);

        return \Botble\Theme\Facades\Theme::of('plugins/hall-of-fame::dashboard.reports', compact('reports', 'researcher'))->render();
    }

    public function reportDetail(Request $request, $id)
    {
        $researcher = $request->hof_researcher;

        // Set theme layout and breadcrumbs
        \Botble\Theme\Facades\Theme::setLayout('hall-of-fame');
        \Botble\Theme\Facades\Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(trans('plugins/hall-of-fame::vulnerability-reports.hall_of_fame'), route('public.hall-of-fame.index'))
            ->add('Dashboard', route('public.hall-of-fame.dashboard.index'))
            ->add('My Reports', route('public.hall-of-fame.dashboard.reports'))
            ->add('Report Details', '');
        
        $report = VulnerabilityReport::where('researcher_email', $researcher->email)
            ->where('id', $id)
            ->with(['attachments', 'certificate'])
            ->firstOrFail();

        return \Botble\Theme\Facades\Theme::of('plugins/hall-of-fame::dashboard.report-detail', compact('report', 'researcher'))->render();
    }

    public function analytics(Request $request)
    {
        $researcher = $request->hof_researcher;

        // Set theme layout and breadcrumbs
        \Botble\Theme\Facades\Theme::setLayout('hall-of-fame');
        \Botble\Theme\Facades\Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(trans('plugins/hall-of-fame::vulnerability-reports.hall_of_fame'), route('public.hall-of-fame.index'))
            ->add('Dashboard', route('public.hall-of-fame.dashboard.index'))
            ->add('Analytics', route('public.hall-of-fame.dashboard.analytics'));
        
        // Comprehensive analytics data
        $analytics = [
            'total_reports' => VulnerabilityReport::where('researcher_email', $researcher->email)->count(),
            'published_reports' => VulnerabilityReport::where('researcher_email', $researcher->email)->where('status', 'published')->count(),
            'pending_reports' => VulnerabilityReport::where('researcher_email', $researcher->email)->where('status', 'pending')->count(),
            'rejected_reports' => VulnerabilityReport::where('researcher_email', $researcher->email)->where('status', 'rejected')->count(),
            'certificates_earned' => Certificate::whereHas('vulnerabilityReport', function($q) use ($researcher) {
                $q->where('researcher_email', $researcher->email);
            })->count(),
        ];

        // Success rate
        $analytics['success_rate'] = $analytics['total_reports'] > 0 
            ? round(($analytics['published_reports'] / $analytics['total_reports']) * 100, 1) 
            : 0;

        // Vulnerability types distribution
        $vulnerabilityTypes = VulnerabilityReport::where('researcher_email', $researcher->email)
            ->selectRaw('vulnerability_type, count(*) as count')
            ->groupBy('vulnerability_type')
            ->orderBy('count', 'desc')
            ->get();

        // Monthly submissions over the last year
        $monthlyData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = VulnerabilityReport::where('researcher_email', $researcher->email)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            
            $monthlyData[] = [
                'month' => $date->format('M Y'),
                'count' => $count
            ];
        }

        // Status timeline
        $statusTimeline = VulnerabilityReport::where('researcher_email', $researcher->email)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get();

        return \Botble\Theme\Facades\Theme::of('plugins/hall-of-fame::dashboard.analytics', compact(
            'analytics', 'vulnerabilityTypes', 'monthlyData', 'statusTimeline', 'researcher'
        ))->render();
    }
}
