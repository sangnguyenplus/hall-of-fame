@include('plugins/hall-of-fame::partials.hof-master')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="alert alert-success">
                <h4>{{ trans('plugins/hall-of-fame::vulnerability-reports.thank_you_for_your_submission') }}</h4>
                <p>{{ trans('plugins/hall-of-fame::vulnerability-reports.thank_you_message') }}</p>
                <p class="mb-0">{{ trans('plugins/hall-of-fame::vulnerability-reports.reference_id') }}:
                    <strong>{{ $report->id }}</strong>
                </p>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('public.hall-of-fame.index') }}" class="btn btn-primary">
                    {{ trans('plugins/hall-of-fame::vulnerability-reports.view_hall_of_fame') }}
                </a>
            </div>
        </div>
    </div>
</div>
