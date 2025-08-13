@include('plugins/hall-of-fame::partials.hof-master')

<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <h1 class="hof-title">{{ __('Hall of Fame') }}</h1>
            <p class="hof-subtitle">
                {{ __('Thank you to all the security researchers who have helped us keep our platform secure.') }}</p>
        </div>
    </div>

    <div class="row mt-4">
        @if (isset($reports) && $reports->count() > 0)
            <div class="col-12">
                <div class="hof-card">
                    <div class="hof-card-content">
                        <div class="hof-table-container">
                            <table class="hof-table">
                                <thead>
                                    <tr>
                                        <th>{{ __('Researcher') }}</th>
                                        <th>{{ __('Vulnerability') }}</th>
                                        <th>{{ __('Date') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reports as $report)
                                        <tr>
                                            <td>{{ $report->researcher->name ?? $report->researcher_name }}</td>
                                            <td>{{ $report->title }}</td>
                                            <td>{{ $report->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {!! $reports->links() !!}
            </div>
        @else
            <div class="col-12">
                <div class="hof-empty-state">
                    <i class="fas fa-search"></i>
                    <p>{{ __('No reports found.') }}</p>
                </div>
            </div>
        @endif
    </div>
</div>
