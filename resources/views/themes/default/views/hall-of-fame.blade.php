@extends('plugins/hall-of-fame::themes.default.layouts.master')

@section('content')
    <div class="container">
        <div class="section-title text-center mb-5">
            <h2>{{ trans('plugins/hall-of-fame::vulnerability-reports.hall_of_fame') }}</h2>
            <p>{{ trans('plugins/hall-of-fame::vulnerability-reports.hall_of_fame_description') }}</p>
        </div>

        <div class="row">
            @if ($reports->isEmpty())
                <div class="col-12 text-center">
                    <p>{{ trans('plugins/hall-of-fame::vulnerability-reports.no_reports') }}</p>
                </div>
            @else
                @foreach ($reports as $report)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="{{ route('public.vulnerability-reports.show', $report->id) }}" class="text-dark">
                                        {{ $report->title }}
                                    </a>
                                </h5>
                                <p class="card-text text-muted">
                                    <small>
                                        {{ trans('plugins/hall-of-fame::vulnerability-reports.reported_by') }}:
                                        <strong>{{ $report->researcher_name }}</strong>
                                    </small>
                                </p>
                                <p class="card-text">{{ Str::limit($report->description, 150) }}</p>
                            </div>
                            <div class="card-footer bg-white">
                                <small class="text-muted">
                                    {{ trans('plugins/hall-of-fame::vulnerability-reports.reported_on') }}:
                                    {{ $report->created_at->format('M d, Y') }}
                                </small>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="col-12 mt-4">
                    {!! $reports->links('core/base::layouts.partials.pagination') !!}
                </div>
            @endif
        </div>

        <div class="text-center mt-5">
            <a href="{{ route('public.vulnerability-reports.create') }}" class="btn btn-primary">
                {{ trans('plugins/hall-of-fame::vulnerability-reports.submit_a_vulnerability') }}
            </a>
        </div>
    </div>
@endsection

@push('footer')
    {!! Theme::asset()->container('footer')->render('hall-of-fame-js') !!}
@endpush
