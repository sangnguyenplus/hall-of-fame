@include('plugins/hall-of-fame::partials.hof-master')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h1 class="hof-title text-center mb-5">
                {{ trans('plugins/hall-of-fame::vulnerability-reports.hall_of_fame') }}</h1>

            <div class="hof-info-banner mb-4">
                <p>{{ trans('plugins/hall-of-fame::vulnerability-reports.hall_of_fame_description') }}</p>
                <p class="mb-0">
                    <a href="{{ route('public.vulnerability-reports.create') }}" class="hof-btn hof-btn-primary">
                        {{ trans('plugins/hall-of-fame::vulnerability-reports.submit_a_vulnerability') }}
                    </a>
                </p>
            </div>

            <div class="row mb-5">
                @if ($reports->count() > 0)
                    @foreach ($reports as $report)
                        <div class="col-md-6 mb-4">
                            <div class="hof-card hof-report-card h-100">
                                <div class="hof-card-content">
                                    <h4 class="hof-card-title">{{ $report->title }}</h4>
                                    <h6 class="hof-card-subtitle">
                                        {{ $report->vulnerability_type }} - {{ $report->created_at->format('M d, Y') }}
                                    </h6>
                                    <p class="hof-card-text">
                                        <strong>{{ trans('plugins/hall-of-fame::vulnerability-reports.researcher') }}:</strong>
                                        {{ $report->researcher_name }}
                                    </p>
                                </div>
                                <div class="hof-card-footer">
                                    <a href="{{ route('public.vulnerability-reports.show', $report->id) }}"
                                        class="hof-btn hof-btn-sm hof-btn-outline">
                                        {{ trans('plugins/hall-of-fame::vulnerability-reports.view_details') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="hof-empty-state">
                            <i class="fas fa-search"></i>
                            <p>{{ trans('plugins/hall-of-fame::vulnerability-reports.no_reports_found') }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="hof-pagination-wrapper">
                {!! $reports->links() !!}
            </div>
        </div>
    </div>
</div>
