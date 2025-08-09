@extends('plugins/hall-of-fame::layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h1 class="text-center mb-5">
                    {{ trans('plugins/hall-of-fame::vulnerability-reports.submit_a_vulnerability') }}</h1>

                <div class="card">
                    <div class="card-body">
                        <div class="alert alert-info">
                            <h4>{{ trans('plugins/hall-of-fame::vulnerability-reports.submission_guidelines') }}</h4>
                            <ul>
                                <li>{{ trans('plugins/hall-of-fame::vulnerability-reports.guideline_1') }}</li>
                                <li>{{ trans('plugins/hall-of-fame::vulnerability-reports.guideline_2') }}</li>
                                <li>{{ trans('plugins/hall-of-fame::vulnerability-reports.guideline_3') }}</li>
                                <li>{{ trans('plugins/hall-of-fame::vulnerability-reports.guideline_4') }}</li>
                                <li>{{ trans('plugins/hall-of-fame::vulnerability-reports.guideline_5') }}</li>
                            </ul>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {!! Form::open([
                            'route' => 'public.vulnerability-reports.store',
                            'method' => 'POST',
                            'files' => true,
                            'class' => 'vulnerability-report-form',
                        ]) !!}

                        {!! $form->renderForm() !!}

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
