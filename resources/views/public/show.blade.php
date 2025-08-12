@extends(Theme::getLayout())

@section('content')
    @include('plugins/hall-of-fame::partials.hof-navigation')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="mb-4">
                    <a href="{{ route('public.hall-of-fame.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i>
                        {{ trans('plugins/hall-of-fame::vulnerability-reports.back_to_hall_of_fame') }}
                    </a>
                </div>

                <div class="card mb-5">
                    <div class="card-body">
                        <h1 class="card-title">{{ $report->title }}</h1>
                        <div class="card-subtitle mb-4">
                            <div>
                                <span class="badge bg-primary">{{ $report->vulnerability_type }}</span>
                                <span
                                    class="text-muted ms-2">{{ trans('plugins/hall-of-fame::vulnerability-reports.reported_on') }}:
                                    {{ $report->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>

                        <h4>{{ trans('plugins/hall-of-fame::vulnerability-reports.description') }}</h4>
                        <div class="mb-4">
                            {!! clean($report->description) !!}
                        </div>

                        @if ($report->endpoint)
                            <h4>{{ trans('plugins/hall-of-fame::vulnerability-reports.endpoint') }}</h4>
                            <div class="mb-4">
                                <code>{{ $report->endpoint }}</code>
                            </div>
                        @endif

                        <h4>{{ trans('plugins/hall-of-fame::vulnerability-reports.impact') }}</h4>
                        <div class="mb-4">
                            {!! clean($report->impact) !!}
                        </div>

                        @if ($report->suggested_fix)
                            <h4>{{ trans('plugins/hall-of-fame::vulnerability-reports.suggested_fix') }}</h4>
                            <div class="mb-4">
                                {!! clean($report->suggested_fix) !!}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="researcher-avatar me-3">
                                <img src="{{ get_gravatar($report->researcher_email, 80) }}"
                                    alt="{{ $report->researcher_name }}" class="rounded-circle">
                            </div>
                            <div>
                                <h4 class="mb-1">{{ $report->researcher_name }}</h4>
                                @if ($report->researcher_bio)
                                    <p class="mb-0">{{ $report->researcher_bio }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
