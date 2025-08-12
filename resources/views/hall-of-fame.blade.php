@include('plugins/hall-of-fame::partials.hof-navigation')

<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <h1>{{ __('Hall of Fame') }}</h1>
            <p>{{ __('Thank you to all the security researchers who have helped us keep our platform secure.') }}</p>
        </div>
    </div>

    <div class="row mt-4">
        @if (isset($reports) && $reports->count() > 0)
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
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

                {!! $reports->links() !!}
            </div>
        @else
            <div class="col-12">
                <div class="alert alert-info">
                    {{ __('No reports found.') }}
                </div>
            </div>
        @endif
    </div>
</div>
