@include('plugins/hall-of-fame::partials.hof-master')

<div class="hof-container">
    <div class="container-fluid">
        <div class="dashboard-header mb-4 hof-animate-fade-in">
            <div class="row align-items-center">
                <div class="col">
                    <h1 class="hof-display-2 mb-2">{{ trans('plugins/hall-of-fame::dashboard.dashboard') }}</h1>
                    <p class="text-muted mb-0 fs-5">{{ trans('plugins/hall-of-fame::auth.welcome') }}, <span
                            class="hof-text-gradient">{{ $researcher->name }}</span>!</p>
                </div>
                <div class="col-auto">
                    <a href="{{ route('public.vulnerability-reports.create') }}"
                        class="hof-btn hof-btn-primary hof-btn-lg">
                        <i class="fas fa-plus"></i>
                        {{ trans('plugins/hall-of-fame::dashboard.submit_new_report') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Modern Statistics Cards -->
        <div class="row mb-5">
            <div class="col-md-3 mb-4">
                <div class="hof-stat-card hof-animate-slide-in" style="animation-delay: 0.1s">
                    <div class="hof-stat-value text-primary">{{ $stats['total_reports'] }}</div>
                    <div class="hof-stat-label">{{ trans('plugins/hall-of-fame::dashboard.total_reports') }}</div>
                    <i class="fas fa-clipboard-list hof-stat-icon"></i>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="hof-stat-card hof-animate-slide-in" style="animation-delay: 0.2s">
                    <div class="hof-stat-value text-success">{{ $stats['published_reports'] }}</div>
                    <div class="hof-stat-label">{{ trans('plugins/hall-of-fame::dashboard.published_reports') }}</div>
                    <i class="fas fa-check-circle hof-stat-icon"></i>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="hof-stat-card hof-animate-slide-in" style="animation-delay: 0.3s">
                    <div class="hof-stat-value text-warning">{{ $stats['pending_reports'] }}</div>
                    <div class="hof-stat-label">{{ trans('plugins/hall-of-fame::dashboard.pending_reports') }}</div>
                    <i class="fas fa-clock hof-stat-icon"></i>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="hof-stat-card hof-animate-slide-in" style="animation-delay: 0.4s">
                    <div class="hof-stat-value text-info">{{ $stats['certificates'] }}</div>
                    <div class="hof-stat-label">{{ trans('plugins/hall-of-fame::dashboard.certificates') }}</div>
                    <i class="fas fa-certificate hof-stat-icon"></i>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Reports & Activity -->
            <div class="col-lg-8 mb-4">
                <div class="hof-card">
                    <div class="hof-card-header">
                        <h5 class="hof-card-title mb-0">{{ trans('plugins/hall-of-fame::dashboard.recent_activity') }}
                        </h5>
                        <a href="{{ route('public.hall-of-fame.dashboard.reports') }}" class="hof-btn hof-btn-outline">
                            {{ trans('plugins/hall-of-fame::dashboard.view_all_reports') }}
                        </a>
                    </div>
                    <div class="hof-card-content">
                        @if ($recentActivity->count() > 0)
                            <div class="hof-activity-list">
                                @foreach ($recentActivity as $activity)
                                    <div class="hof-activity-item">
                                        <div
                                            class="hof-activity-icon hof-activity-{{ $activity->status == 'published' ? 'success' : ($activity->status == 'pending' ? 'warning' : 'info') }}">
                                            <i
                                                class="fas fa-{{ $activity->status == 'published' ? 'check' : ($activity->status == 'pending' ? 'clock' : 'bug') }}"></i>
                                        </div>
                                        <div class="hof-activity-content">
                                            <div class="hof-activity-title">{{ $activity->title }}</div>
                                            <div class="hof-activity-meta">
                                                <span
                                                    class="hof-badge hof-badge-{{ $activity->status == 'published' ? 'success' : ($activity->status == 'pending' ? 'warning' : 'info') }}">
                                                    {{ ucfirst($activity->status) }}
                                                </span>
                                                <span
                                                    class="hof-text-muted">{{ $activity->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                        <div class="hof-activity-actions">
                                            <a href="{{ route('public.hall-of-fame.dashboard.reports.detail', $activity->id) }}"
                                                class="hof-btn hof-btn-sm hof-btn-outline">
                                                {{ trans('plugins/hall-of-fame::dashboard.view') }}
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="hof-empty-state">
                                <i class="fas fa-history"></i>
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
                                    <span
                                        class="text-sm">{{ trans('plugins/hall-of-fame::dashboard.success_rate') }}</span>
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

                        @if ($researcherRank)
                            <div class="text-center py-3 border-top">
                                <div class="h4 mb-1 text-gradient">#{{ $researcherRank }}</div>
                                <div class="text-sm text-muted">
                                    {{ trans('plugins/hall-of-fame::dashboard.your_rank') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Recent Certificates -->
                @if ($certificates->count() > 0)
                    <div class="hof-card">
                        <div class="hof-card-header">
                            <h6 class="hof-card-title mb-0">
                                {{ trans('plugins/hall-of-fame::dashboard.your_certificates') }}</h6>
                            <a href="{{ route('public.hall-of-fame.dashboard.certificates') }}"
                                class="hof-btn hof-btn-outline">
                                {{ trans('plugins/hall-of-fame::dashboard.view_certificates') }}
                            </a>
                        </div>
                        <div class="hof-card-content">
                            @foreach ($certificates as $certificate)
                                <div class="hof-certificate-card mb-3">
                                    <div class="hof-certificate-badge">
                                        <i class="fas fa-check"></i>
                                    </div>
                                    <div class="hof-certificate-content">
                                        <h6 class="hof-certificate-title">{{ $certificate->vulnerability_title }}</h6>
                                        <p class="hof-certificate-id">{{ $certificate->certificate_id }}</p>
                                    </div>
                                    <div class="hof-certificate-actions">
                                        <a href="{{ route('public.certificates.view', $certificate->certificate_id) }}"
                                            class="hof-btn hof-btn-sm hof-btn-outline">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('public.certificates.download', $certificate->certificate_id) }}"
                                            class="hof-btn hof-btn-sm hof-btn-success">
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
                    <div class="hof-card">
                        <div class="hof-card-header">
                            <h6 class="hof-card-title mb-0">
                                {{ trans('plugins/hall-of-fame::dashboard.vulnerability_breakdown') }}</h6>
                        </div>
                        <div class="hof-card-content">
                            <div class="hof-chart-container">
                                <canvas id="vulnerabilityTypesChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Monthly Reports Chart -->
                <div class="col-lg-6 mb-4">
                    <div class="hof-card">
                        <div class="hof-card-header">
                            <h6 class="hof-card-title mb-0">
                                {{ trans('plugins/hall-of-fame::dashboard.submission_trends') }}</h6>
                        </div>
                        <div class="hof-card-content">
                            <div class="hof-chart-container">
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
