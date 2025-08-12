@include('plugins/hall-of-fame::partials.hof-navigation')

<div class="dashboard-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3 mb-1">{{ trans('plugins/hall-of-fame::dashboard.dashboard') }}</h1>
            <p class="text-muted mb-0">{{ trans('plugins/hall-of-fame::auth.welcome') }}, {{ $user->name }}!</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('public.vulnerability-reports.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>
                {{ trans('plugins/hall-of-fame::dashboard.submit_new_report') }}
            </a>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="stat-card stat-primary position-relative">
            <div class="stat-value">{{ $stats['total_reports'] }}</div>
            <div class="stat-label">{{ trans('plugins/hall-of-fame::dashboard.total_reports') }}</div>
            <i class="fas fa-bug stat-icon"></i>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-card stat-success position-relative">
            <div class="stat-value">{{ $stats['published_reports'] }}</div>
            <div class="stat-label">{{ trans('plugins/hall-of-fame::dashboard.published_reports') }}</div>
            <i class="fas fa-check-circle stat-icon"></i>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-card stat-warning position-relative">
            <div class="stat-value">{{ $stats['pending_reports'] }}</div>
            <div class="stat-label">{{ trans('plugins/hall-of-fame::dashboard.pending_reports') }}</div>
            <i class="fas fa-clock stat-icon"></i>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="stat-card stat-info position-relative">
            <div class="stat-value">{{ $stats['certificates'] }}</div>
            <div class="stat-label">{{ trans('plugins/hall-of-fame::dashboard.certificates_earned') }}</div>
            <i class="fas fa-certificate stat-icon"></i>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Reports & Activity -->
    <div class="col-lg-8 mb-4">
        <div class="dashboard-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ trans('plugins/hall-of-fame::dashboard.recent_activity') }}</h5>
                <a href="{{ route('public.hall-of-fame.dashboard.reports') }}" class="btn btn-sm btn-outline-primary">
                    {{ trans('plugins/hall-of-fame::dashboard.view_all_reports') }}
                </a>
            </div>
            <div class="card-body">
                @if ($recentActivity->count() > 0)
                    @foreach ($recentActivity as $activity)
                        <div class="activity-item">
                            <div
                                class="activity-icon activity-{{ $activity->status == 'published' ? 'success' : ($activity->status == 'pending' ? 'warning' : 'info') }}">
                                <i
                                    class="fas fa-{{ $activity->status == 'published' ? 'check' : ($activity->status == 'pending' ? 'clock' : 'bug') }}"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">{{ $activity->title }}</div>
                                <div class="activity-meta">
                                    <span class="status-badge status-{{ $activity->status }}">
                                        {{ ucfirst($activity->status) }}
                                    </span>
                                    <span class="ms-2 text-muted">{{ $activity->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="activity-actions">
                                <a href="{{ route('public.hall-of-fame.dashboard.reports.detail', $activity->id) }}"
                                    class="btn btn-sm btn-outline-secondary">
                                    {{ trans('plugins/hall-of-fame::dashboard.view') }}
                                </a>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-history fa-3x mb-3 opacity-25"></i>
                        <p>{{ trans('plugins/hall-of-fame::auth.no_submissions') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Stats & Actions -->
    <div class="col-lg-4 mb-4">
        <!-- Performance Summary -->
        <div class="dashboard-card mb-4">
            <div class="card-header">
                <h6 class="mb-0">{{ trans('plugins/hall-of-fame::dashboard.performance_overview') }}</h6>
            </div>
            <div class="card-body">
                @if ($stats['total_reports'] > 0)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-sm">{{ trans('plugins/hall-of-fame::dashboard.success_rate') }}</span>
                            <span class="text-sm font-weight-bold">
                                {{ $stats['total_reports'] > 0 ? round(($stats['published_reports'] / $stats['total_reports']) * 100, 1) : 0 }}%
                            </span>
                        </div>
                        <div class="progress progress-sm">
                            <div class="progress-bar bg-success"
                                style="width: {{ $stats['total_reports'] > 0 ? ($stats['published_reports'] / $stats['total_reports']) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>
                @endif

                @if ($userRank)
                    <div class="text-center py-3 border-top">
                        <div class="h4 mb-1 text-gradient">#{{ $userRank }}</div>
                        <div class="text-sm text-muted">{{ trans('plugins/hall-of-fame::dashboard.your_rank') }}</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Certificates -->
        @if ($certificates->count() > 0)
            <div class="dashboard-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">{{ trans('plugins/hall-of-fame::dashboard.your_certificates') }}</h6>
                    <a href="{{ route('public.hall-of-fame.dashboard.certificates') }}"
                        class="btn btn-sm btn-outline-primary">
                        {{ trans('plugins/hall-of-fame::dashboard.view_certificates') }}
                    </a>
                </div>
                <div class="card-body">
                    @foreach ($certificates as $certificate)
                        <div class="certificate-card p-3 mb-3 position-relative">
                            <div class="certificate-badge certificate-verified">
                                <i class="fas fa-check"></i>
                            </div>
                            <h6 class="mb-1">{{ $certificate->vulnerability_title }}</h6>
                            <p class="text-muted mb-2 small">{{ $certificate->certificate_id }}</p>
                            <div class="d-flex gap-2">
                                <a href="{{ route('public.certificates.view', $certificate->certificate_id) }}"
                                    class="btn btn-action btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('public.certificates.download', $certificate->certificate_id) }}"
                                    class="btn btn-action btn-outline-success">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Vulnerability Types Chart -->
@if (count($vulnerabilityTypes) > 0)
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="dashboard-card">
                <div class="card-header">
                    <h6 class="mb-0">{{ trans('plugins/hall-of-fame::dashboard.vulnerability_breakdown') }}</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="vulnerabilityTypesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Reports Chart -->
        <div class="col-lg-6 mb-4">
            <div class="dashboard-card">
                <div class="card-header">
                    <h6 class="mb-0">{{ trans('plugins/hall-of-fame::dashboard.submission_trends') }}</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="monthlyReportsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<script>
    $(document).ready(function() {
        // Vulnerability Types Pie Chart
        @if (count($vulnerabilityTypes) > 0)
            const vulnTypesCtx = document.getElementById('vulnerabilityTypesChart').getContext('2d');
            new Chart(vulnTypesCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode(array_keys($vulnerabilityTypes)) !!},
                    datasets: [{
                        data: {!! json_encode(array_values($vulnerabilityTypes)) !!},
                        backgroundColor: [
                            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                            '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        }
                    }
                }
            });
        @endif

        // Monthly Reports Line Chart
        @if (count($monthlyReports) > 0)
            const monthlyCtx = document.getElementById('monthlyReportsChart').getContext('2d');
            new Chart(monthlyCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($monthlyReports->pluck('date')) !!},
                    datasets: [{
                        label: 'Reports',
                        data: {!! json_encode($monthlyReports->pluck('count')) !!},
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#667eea',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        @endif
    });
</script>
