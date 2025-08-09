@extends('plugins/hall-of-fame::layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h1 class="text-center mb-5">{{ trans('plugins/hall-of-fame::vulnerability-reports.hall_of_fame') }}</h1>

                <div class="alert alert-info mb-4">
                    <p>{{ trans('plugins/hall-of-fame::vulnerability-reports.hall_of_fame_description') }}</p>
                    <p class="mb-0">
                        <a href="{{ route('public.vulnerability-reports.create') }}" class="btn btn-primary">
                            {{ trans('plugins/hall-of-fame::vulnerability-reports.submit_a_vulnerability') }}
                        </a>
                    </p>
                </div>

                <div class="row mb-5">
                    @if ($reports->count() > 0)
                        @foreach ($reports as $report)
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <h4 class="card-title">{{ $report->title }}</h4>
                                        <h6 class="card-subtitle mb-2 text-muted">
                                            {{ $report->vulnerability_type }} - {{ $report->created_at->format('M d, Y') }}
                                        </h6>
                                        <p class="card-text">
                                            <strong>{{ trans('plugins/hall-of-fame::vulnerability-reports.researcher') }}:
                                            </strong>
                                            {{ $report->researcher_name }}
                                        </p>
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
                    @else
                        <div class="col-12">
                            <div class="alert alert-warning">
                                {{ trans('plugins/hall-of-fame::vulnerability-reports.no_reports_found') }}
                            </div>
                        </div>
                    @endif
                </div>

                <div class="d-flex justify-content-center">
                    {!! $reports->links() !!}
                </div>
            </div>
        </div>
    </div>
@stop
