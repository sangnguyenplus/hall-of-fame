<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        try {
            if (class_exists('Botble\\Base\\Facades\\Meta')) {
                echo Botble\Base\Facades\Meta::toHtml();
            }
        } catch (Throwable $e) {
            // silently ignore if Meta facade/service is unavailable
        }
        try {
            if (class_exists('Botble\\SeoHelper\\Facades\\SeoHelper')) {
                echo Botble\SeoHelper\Facades\SeoHelper::render();
            }
        } catch (Throwable $e) {
            // silently ignore if SeoHelper facade/service is unavailable
        }
    @endphp

    <link href="{{ asset('vendor/core/core/base/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/core/plugins/hall-of-fame/css/hall-of-fame.css') }}" rel="stylesheet">

    <!-- Theme style -->
    @php $themeAvailable = class_exists('Theme'); @endphp
    @if ($themeAvailable)
        {!! Theme::header() !!}
    @endif

    @stack('header')

    <style>
        .required {
            color: red;
        }

        .invalid-feedback {
            display: block;
            color: red;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    @if ($themeAvailable)
        {!! Theme::partial('header') !!}
    @endif

    <div class="main-content py-5">
        @yield('content')
    </div>

    @if ($themeAvailable)
        {!! Theme::partial('footer') !!}
    @endif

    <script src="{{ asset('vendor/core/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/core/core/base/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/core/plugins/hall-of-fame/js/hall-of-fame.js') }}"></script>

    <!-- Theme scripts -->
    @if ($themeAvailable)
        {!! Theme::footer() !!}
    @endif

    @stack('footer')
</body>

</html>
