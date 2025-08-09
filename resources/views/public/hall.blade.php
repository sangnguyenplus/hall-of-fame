@extends('plugins/hall-of-fame::layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h1 class="text-center mb-4">{{ trans('plugins/hall-of-fame::vulnerability-reports.hall_of_fame') }}</h1>
                <p class="text-center mb-5">
                    {{ trans('plugins/hall-of-fame::vulnerability-reports.hall_of_fame_description') }}</p>

                <div class="mb-4">
                    <a href="{{ route('public.vulnerability-reports.create') }}" class="btn btn-primary mb-4">
                        {{ trans('plugins/hall-of-fame::vulnerability-reports.submit_a_vulnerability') }}
                    </a>

                    <div class="nav nav-tabs mb-4">
                        @foreach ($years as $year)
                            <a class="nav-link @if ($loop->first) active @endif"
                                href="#year-{{ $year }}" data-bs-toggle="tab">{{ $year }}</a>
                        @endforeach
                    </div>

                    <div class="tab-content">
                        @foreach ($years as $year)
                            <div class="tab-pane fade @if ($loop->first) show active @endif"
                                id="year-{{ $year }}">
                                @if (!empty($reports[$year]) && count($reports[$year]) > 0)
                                    <div class="row">
                                        @foreach ($reports[$year] as $report)
                                            <div class="col-md-6 mb-4">
                                                <div class="card h-100">
                                                    <div class="card-body">
                                                        <h4 class="card-title">{{ $report->title }}</h4>
                                                        <h6 class="card-subtitle mb-2 text-muted">
                                                            {{ $report->vulnerability_type }} -
                                                            {{ $report->created_at->format('M d, Y') }}
                                                        </h6>
                                                        <div class="d-flex align-items-center mt-3">
                                                            <div class="researcher-avatar me-2">
                                                                <img src="{{ get_gravatar($report->researcher_email, 32) }}"
                                                                    alt="{{ $report->researcher_name }}"
                                                                    class="rounded-circle">
                                                            </div>
                                                            <div>
                                                                <span class="fw-bold">{{ $report->researcher_name }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-footer bg-white border-0 text-end">
                                                        <a href="{{ route('public.vulnerability-reports.show', $report->id) }}"
                                                            class="btn btn-sm btn-outline-primary">
                                                            {{ trans('plugins/hall-of-fame::vulnerability-reports.view_details') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="alert alert-warning">
                                        {{ trans('plugins/hall-of-fame::vulnerability-reports.no_reports_found_for_year', ['year' => $year]) }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
