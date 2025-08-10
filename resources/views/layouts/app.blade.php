@extends(Theme::getThemeNamespace() . '::layouts.default')

@section('content')
    {!! Theme::breadcrumb()->render() !!}

    <div class="container">
        <div class="row">
            <div class="col-12">
                @yield('hall-of-fame-content')
            </div>
        </div>
    </div>
@stop
