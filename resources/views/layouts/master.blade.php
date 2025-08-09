<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {!! Meta::toHtml() !!}

    {!! SeoHelper::render() !!}

    <link href="{{ asset('vendor/core/core/base/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/core/plugins/hall-of-fame/css/hall-of-fame.css') }}" rel="stylesheet">

    <!-- Theme style -->
    {!! Theme::header() !!}

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
    {!! Theme::partial('header') !!}

    <div class="main-content py-5">
        @yield('content')
    </div>

    {!! Theme::partial('footer') !!}

    <script src="{{ asset('vendor/core/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/core/core/base/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/core/plugins/hall-of-fame/js/hall-of-fame.js') }}"></script>

    <!-- Theme scripts -->
    {!! Theme::footer() !!}

    @stack('footer')
</body>

</html>
