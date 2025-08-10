<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="index, follow">

    {!! Theme::header() !!}
</head>

<body @if (BaseHelper::siteLanguageDirection() == 'rtl') dir="rtl" @endif class="martfury">
    {!! Theme::partial('preloader') !!}

    <div class="martfury-layout">
        <header class="header header--1" data-sticky="{{ Theme::get('sticky_header', 'yes') }}">
            {!! Theme::partial('header') !!}
        </header>

        <div class="ps-page--default">
            <div class="ps-breadcrumb">
                <div class="container">
                    {!! Theme::partial('breadcrumb') !!}
                </div>
            </div>

            <div class="container">
                <div class="ps-section--custom">
                    @yield('content')
                </div>
            </div>
        </div>

        {!! Theme::partial('footer') !!}
    </div>

    {!! Theme::partial('mobile-menu') !!}

    {!! Theme::partial('search-mobile') !!}

    {!! Theme::footer() !!}
</body>

</html>
