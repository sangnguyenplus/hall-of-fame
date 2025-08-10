@extends('plugins/hall-of-fame::layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card my-5">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">{{ trans('plugins/hall-of-fame::auth.dashboard') }}</h3>
                        <div>
                            <a href="{{ route('public.vulnerability-reports.create') }}" class="btn btn-primary">
                                {{ trans('plugins/hall-of-fame::vulnerability-reports.submit_a_vulnerability') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <h4 class="mb-3">{{ trans('plugins/hall-of-fame::auth.welcome') }}, {{ Auth::user()->name }}!</h4>

                        <div class="dashboard-info mb-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h5>{{ trans('plugins/hall-of-fame::auth.total_submissions') }}</h5>
                                            <h3>{{ count($reports) }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
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
@endsection
