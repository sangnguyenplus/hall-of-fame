@include('plugins/hall-of-fame::partials.hof-master')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="mb-4">
                <a href="{{ route('public.hall-of-fame.index') }}" class="hof-btn hof-btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    {{ trans('plugins/hall-of-fame::vulnerability-reports.back_to_hall_of_fame') }}
                </a>
            </div>

            <div class="hof-card mb-5">
                <div class="hof-card-content">
                    <h1 class="hof-card-title">{{ $report->title }}</h1>
                    <div class="hof-card-subtitle mb-4">
                        <div>
                            <span class="hof-badge hof-badge-primary">{{ $report->vulnerability_type }}</span>
                            <span
                                class="hof-text-muted ms-2">{{ trans('plugins/hall-of-fame::vulnerability-reports.reported_on') }}:
                                {{ $report->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>

                    <h4 class="hof-section-title">{{ trans('plugins/hall-of-fame::vulnerability-reports.description') }}
                    </h4>
                    <div class="hof-content-section mb-4">
                        {!! clean($report->description) !!}
                    </div>

                    @if ($report->endpoint)
                        <h4 class="hof-section-title">
                            {{ trans('plugins/hall-of-fame::vulnerability-reports.endpoint') }}</h4>
                        <div class="hof-code-block mb-4">
                            <code>{{ $report->endpoint }}</code>
                        </div>
                    @endif

                    <h4 class="hof-section-title">{{ trans('plugins/hall-of-fame::vulnerability-reports.impact') }}</h4>
                    <div class="hof-content-section mb-4">
                        {!! clean($report->impact) !!}
                    </div>

                    @if ($report->suggested_fix)
                        <h4 class="hof-section-title">
                            {{ trans('plugins/hall-of-fame::vulnerability-reports.suggested_fix') }}</h4>
                        <div class="hof-content-section mb-4">
                            {!! clean($report->suggested_fix) !!}
                        </div>
                    @endif
                </div>
            </div>

            <div class="hof-card">
                <div class="hof-card-content">
                    <div class="hof-researcher-profile">
                        <div class="hof-researcher-avatar">
                            <img src="{{ get_gravatar($report->researcher_email, 80) }}"
                                alt="{{ $report->researcher_name }}" class="hof-avatar hof-avatar-lg">
                        </div>
                        <div class="hof-researcher-info">
                            <h4 class="hof-researcher-name">{{ $report->researcher_name }}</h4>
                            @if ($report->researcher_bio)
                                <p class="hof-researcher-bio">{{ $report->researcher_bio }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
