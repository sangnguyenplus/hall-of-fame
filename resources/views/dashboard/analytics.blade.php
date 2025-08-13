@include('plugins/hall-of-fame::partials.hof-master')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">{{ trans('plugins/hall-of-fame::dashboard.analytics') }} -
                {{ trans('plugins/hall-of-fame::dashboard.dashboard') }}</h2>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">{{ trans('plugins/hall-of-fame::dashboard.total_reports') }}</h6>
                            <h2 class="mb-0">{{ $analytics['total_reports'] ?? 0 }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-bug fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">{{ trans('plugins/hall-of-fame::dashboard.verified') }}</h6>
                            <h2 class="mb-0">{{ $analytics['published_reports'] ?? 0 }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">{{ trans('plugins/hall-of-fame::dashboard.under_review') }}</h6>
                            <h2 class="mb-0">{{ $analytics['pending_reports'] ?? 0 }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">{{ trans('plugins/hall-of-fame::dashboard.certificates') }}</h6>
                            <h2 class="mb-0">{{ $analytics['certificates_earned'] ?? 0 }}</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-certificate fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ trans('plugins/hall-of-fame::dashboard.reports_by_severity') }}</h5>
                </div>
                <div class="card-body">
                    <canvas id="severityChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ trans('plugins/hall-of-fame::dashboard.reports_by_status') }}</h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Activity -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ trans('plugins/hall-of-fame::dashboard.monthly_activity') }}</h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ trans('plugins/hall-of-fame::dashboard.recent_activity') }}</h5>
                </div>
                <div class="card-body">
                    @if (isset($recent_activity) && count($recent_activity) > 0)
                        <div class="timeline">
                            @foreach ($recent_activity as $activity)
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-primary"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">
                                            {{ $activity['title'] ?? trans('plugins/hall-of-fame::dashboard.activity') }}
                                        </h6>
                                        <p class="timeline-text">
                                            {{ $activity['description'] ?? trans('plugins/hall-of-fame::dashboard.no_description') }}
                                        </p>
                                        <small
                                            class="text-muted">{{ $activity['date'] ?? trans('plugins/hall-of-fame::dashboard.unknown_date') }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">{{ trans('plugins/hall-of-fame::dashboard.no_activity_data') }}</h4>
                            <p class="text-muted">
                                {{ trans('plugins/hall-of-fame::dashboard.submit_vulnerability_reports_to_see_analytics') }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Severity Chart
        const severityCtx = document.getElementById('severityChart').getContext('2d');
        const severityChart = new Chart(severityCtx, {
            type: 'doughnut',
            data: {
                labels: ['Critical', 'High', 'Medium', 'Low'],
                datasets: [{
                    data: [
                        {{ $charts['severity']['critical'] ?? 0 }},
                        {{ $charts['severity']['high'] ?? 0 }},
                        {{ $charts['severity']['medium'] ?? 0 }},
                        {{ $charts['severity']['low'] ?? 0 }}
                    ],
                    backgroundColor: ['#dc3545', '#fd7e14', '#ffc107', '#6c757d']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Status Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(statusCtx, {
            type: 'pie',
            data: {
                labels: [
                    '{{ trans('plugins/hall-of-fame::dashboard.verified') }}',
                    '{{ trans('plugins/hall-of-fame::dashboard.under_review') }}',
                    '{{ trans('plugins/hall-of-fame::dashboard.rejected') }}',
                    '{{ trans('plugins/hall-of-fame::dashboard.draft') }}'
                ],
                datasets: [{
                    data: [
                        {{ $charts['status']['verified'] ?? 0 }},
                        {{ $charts['status']['under_review'] ?? 0 }},
                        {{ $charts['status']['rejected'] ?? 0 }},
                        {{ $charts['status']['draft'] ?? 0 }}
                    ],
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545', '#6c757d']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });

        // Monthly Chart
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        const monthlyChart = new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($charts['monthly']['labels'] ?? []) !!},
                datasets: [{
                    label: 'Reports Submitted',
                    data: {!! json_encode($charts['monthly']['data'] ?? []) !!},
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endpush
