@include('plugins/hall-of-fame::partials.hof-master')

<div class="hof-dashboard-header mb-4">
    <div class="hof-flex-between">
        <h1 class="hof-title">
            <i class="fas fa-tachometer-alt me-2"></i>
            {{ trans('plugins/hall-of-fame::auth.dashboard') }}
        </h1>
        <a href="{{ route('public.vulnerability-reports.create') }}" class="hof-btn hof-btn-primary hof-btn-lg">
            <i class="fas fa-bug me-2"></i>
            {{ trans('plugins/hall-of-fame::vulnerability-reports.submit_a_vulnerability') }}
        </a>
    </div>
</div>

<div class="hof-success-banner mb-4">
    <i class="fas fa-user-check me-2"></i>
    <strong>{{ trans('plugins/hall-of-fame::auth.welcome') }}, {{ $researcher->name }}!</strong>
    {{ trans('plugins/hall-of-fame::auth.welcome_message') }}
</div>

<div class="hof-stats-grid mb-4">
    <div class="hof-stat-card hof-stat-primary">
        <div class="hof-stat-content">
            <i class="fas fa-file-alt hof-stat-icon"></i>
            <h5>{{ trans('plugins/hall-of-fame::auth.total_submissions') }}</h5>
            <h2>{{ count($reports) }}</h2>
        </div>
    </div>
    <div class="hof-stat-card hof-stat-success">
        <div class="hof-stat-content">
            <i class="fas fa-check-circle hof-stat-icon"></i>
            <h5>{{ trans('plugins/hall-of-fame::auth.approved_submissions') }}</h5>
            <h3>{{ $reports->where('status', 'published')->count() }}</h3>
        </div>
    </div>
    <div class="hof-stat-card hof-stat-warning">
        <div class="hof-stat-content">
            <i class="fas fa-clock hof-stat-icon"></i>
            <h5>{{ trans('plugins/hall-of-fame::auth.pending_submissions') }}</h5>
            <h3>{{ $reports->where('status', 'pending')->count() }}</h3>
        </div>
    </div>
</div>

<h4 class="hof-section-title">{{ trans('plugins/hall-of-fame::auth.your_submissions') }}</h4>
@if ($reports->count() > 0)
    <div class="hof-table-container">
        <table class="hof-table">
            <thead>
                <tr>
                    <th>{{ trans('plugins/hall-of-fame::vulnerability-reports.title') }}</th>
                    <th>{{ trans('plugins/hall-of-fame::vulnerability-reports.vulnerability_type') }}</th>
                    <th>{{ trans('plugins/hall-of-fame::auth.date') }}</th>
                    <th>{{ trans('plugins/hall-of-fame::auth.status') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reports as $report)
                    <tr>
                        <td>{{ $report->title }}</td>
                        <td>{{ $report->vulnerability_type }}</td>
                        <td>{{ $report->created_at->format('Y-m-d') }}</td>
                        <td>{!! $report->getStatusLabel() !!}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="hof-pagination-center">
        {{ $reports->links() }}
    </div>
@else
    <div class="hof-info-banner">
        {{ trans('plugins/hall-of-fame::auth.no_submissions') }}
    </div>
@endif

<div class="hof-logout-section mt-4">
    <form action="{{ route('public.hall-of-fame.auth.logout') }}" method="POST">
        @csrf
        <button type="submit" class="hof-btn hof-btn-danger">
            <i class="fas fa-sign-out-alt me-2"></i>
            {{ trans('plugins/hall-of-fame::auth.logout') }}
        </button>
    </form>
</div>
