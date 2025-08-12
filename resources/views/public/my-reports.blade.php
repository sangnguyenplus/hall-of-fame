@extends(Theme::getLayout())

@section('content')
    @include('plugins/hall-of-fame::partials.hof-navigation')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card my-5">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">{{ trans('plugins/hall-of-fame::vulnerability-reports.my_reports') }}</h3>
                        <div>
                            <a href="{{ route('public.vulnerability-reports.create') }}" class="btn btn-primary">
                                {{ trans('plugins/hall-of-fame::vulnerability-reports.submit_a_vulnerability') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
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
                            <a href="{{ route('public.hall-of-fame.auth.dashboard') }}" class="btn btn-secondary">
                                {{ trans('plugins/hall-of-fame::auth.back_to_dashboard') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
