@include('plugins/hall-of-fame::partials.hof-master')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h1 class="hof-title text-center mb-4">
                {{ trans('plugins/hall-of-fame::vulnerability-reports.hall_of_fame') }}</h1>
            <p class="hof-subtitle text-center mb-5">
                {{ trans('plugins/hall-of-fame::vulnerability-reports.hall_of_fame_description') }}</p>

            <div class="mb-4">
                <a href="{{ route('public.vulnerability-reports.create') }}" class="hof-btn hof-btn-primary mb-4">
                    {{ trans('plugins/hall-of-fame::vulnerability-reports.submit_a_vulnerability') }}
                </a>

                <div class="hof-nav-tabs mb-4">
                    @foreach ($years as $year)
                        <a class="hof-nav-link @if ($loop->first) hof-nav-active @endif"
                            href="#year-{{ $year }}" data-bs-toggle="tab">{{ $year }}</a>
                    @endforeach
                </div>

                <div class="hof-tab-content">
                    @foreach ($years as $year)
                        <div class="hof-tab-pane @if ($loop->first) hof-tab-active @endif"
                            id="year-{{ $year }}">
                            @if (!empty($reports[$year]) && count($reports[$year]) > 0)
                                <div class="row">
                                    @foreach ($reports[$year] as $report)
                                        <div class="col-md-6 mb-4">
                                            <div class="hof-card h-100">
                                                <div class="hof-card-content">
                                                    <h4 class="hof-card-title">{{ $report->title }}</h4>
                                                    <h6 class="hof-card-subtitle">
                                                        {{ $report->vulnerability_type }} -
                                                        {{ $report->created_at->format('M d, Y') }}
                                                    </h6>
                                                    <div class="hof-researcher-info">
                                                        <div class="hof-researcher-avatar">
                                                            <img src="{{ get_gravatar($report->researcher_email, 32) }}"
                                                                alt="{{ $report->researcher_name }}"
                                                                class="hof-avatar">
                                                        </div>
                                                        <div>
                                                            <span
                                                                class="hof-researcher-name">{{ $report->researcher_name }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="hof-card-footer">
                                                    <a href="{{ route('public.vulnerability-reports.show', $report->id) }}"
                                                        class="hof-btn hof-btn-sm hof-btn-primary">
                                                        {{ trans('plugins/hall-of-fame::vulnerability-reports.view_details') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="hof-warning-banner">
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
