@extends('plugins/hall-of-fame::themes.martfury.layouts.master')

@section('content')
    <div class="ps-section ps-hall-of-fame">
        <div class="ps-section__header text-center mb-5">
            <h2>{{ trans('plugins/hall-of-fame::vulnerability-reports.hall_of_fame') }}</h2>
            <p class="ps-desc">{{ trans('plugins/hall-of-fame::vulnerability-reports.hall_of_fame_description') }}</p>
        </div>

        <div class="ps-section__content">
            @if ($reports->isEmpty())
                <div class="text-center">
                    <p>{{ trans('plugins/hall-of-fame::vulnerability-reports.no_reports') }}</p>
                </div>
            @else
                <div class="row">
                    @foreach ($reports as $report)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="ps-block--report">
                                <div class="ps-block__content">
                                    <h5 class="ps-block__title">
                                        <a href="{{ route('public.vulnerability-reports.show', $report->id) }}">
                                            {{ $report->title }}
                                        </a>
                                    </h5>
                                    <p class="ps-block__meta">
                                        {{ trans('plugins/hall-of-fame::vulnerability-reports.reported_by') }}:
                                        <strong>{{ $report->researcher_name }}</strong>
                                    </p>
                                    <p class="ps-block__desc">{{ Str::limit($report->description, 150) }}</p>
                                </div>
                                <div class="ps-block__footer">
                                    <p class="ps-block__date">
                                        {{ trans('plugins/hall-of-fame::vulnerability-reports.reported_on') }}:
                                        {{ $report->created_at->format('M d, Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="ps-pagination">
                    {!! $reports->links('core/base::layouts.partials.pagination') !!}
                </div>
            @endif

            <div class="text-center mt-5">
                <a href="{{ route('public.vulnerability-reports.create') }}" class="ps-btn">
                    {{ trans('plugins/hall-of-fame::vulnerability-reports.submit_a_vulnerability') }}
                </a>
            </div>
        </div>
    </div>
@endsection

@push('footer')
    {!! Theme::asset()->container('footer')->render('hall-of-fame-js') !!}
@endpush

@push('header')
    <style>
        .ps-block--report {
            background-color: #fff;
            padding: 20px;
            height: 100%;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .ps-block--report:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .ps-block--report .ps-block__title {
            margin-bottom: 10px;
        }

        .ps-block--report .ps-block__title a {
            color: #09c;
            text-decoration: none;
        }

        .ps-block--report .ps-block__meta {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
        }

        .ps-block--report .ps-block__desc {
            color: #444;
            line-height: 1.6;
        }

        .ps-block--report .ps-block__footer {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .ps-block--report .ps-block__date {
            font-size: 13px;
            color: #666;
            margin: 0;
        }
    </style>
@endpush
