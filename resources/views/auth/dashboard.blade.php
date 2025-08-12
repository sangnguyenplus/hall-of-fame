@include('plugins/hall-of-fame::partials.hof-navigation')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="display-5 fw-bold text-primary">
        <i class="fas fa-tachometer-alt me-2"></i>
        {{ trans('plugins/hall-of-fame::auth.dashboard') }}
    </h1>
    <a href="{{ route('public.vulnerability-reports.create') }}" class="btn btn-primary btn-lg">
        <i class="fas fa-bug me-2"></i>
        {{ trans('plugins/hall-of-fame::vulnerability-reports.submit_a_vulnerability') }}
    </a>
</div>

<div class="alert alert-success mb-4">
    <i class="fas fa-user-check me-2"></i>
    <strong>{{ trans('plugins/hall-of-fame::auth.welcome') }}, {{ Auth::user()->name }}!</strong>
    Welcome to your security research dashboard.
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <i class="fas fa-file-alt display-4 mb-3"></i>
                <h5>{{ trans('plugins/hall-of-fame::auth.total_submissions') }}</h5>
                <h2>{{ count($reports) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h5>{{ trans('plugins/hall-of-fame::auth.approved_submissions') }}</h5>
                <h3>{{ $reports->where('status', 'published')->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-body text-center">
                <h5>{{ trans('plugins/hall-of-fame::auth.pending_submissions') }}</h5>
                <h3>{{ $reports->where('status', 'pending')->count() }}</h3>
            </div>
        </div>
    </div>
</div>
</div>

<h4>{{ trans('plugins/hall-of-fame::auth.your_submissions') }}</h4>
@if ($reports->count() > 0)
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>{{ trans('plugins/hall-of-fame::vulnerability-reports.title') }}</th>
                    <th>{{ trans('plugins/hall-of-fame::vulnerability-reports.vulnerability_type') }}
                    </th>
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

    <div class="d-flex justify-content-center">
        {{ $reports->links() }}
    </div>
@else
    <div class="alert alert-info">
        {{ trans('plugins/hall-of-fame::auth.no_submissions') }}
    </div>
@endif

<div class="mt-4">
    <form action="{{ route('public.hall-of-fame.auth.logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-danger">
            {{ trans('plugins/hall-of-fame::auth.logout') }}
        </button>
    </form>
</div>
</div>
</div>
</div>
</div>
</div>
