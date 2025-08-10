<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ trans('hall-of-fame.Submit a Vulnerability') }}</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Ensure jQuery is available globally
        if (typeof jQuery !== 'undefined') {
            window.$ = window.jQuery = jQuery;
        }
    </script>
    <style>
        body {
            padding-top: 20px;
            padding-bottom: 20px;
        }

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
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
            <div class="container-fluid">
                <a class="navbar-brand" href="/">{{ trans('general.Who Zid IS') }}</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="/hall-of-fame">{{ trans('hall-of-fame.Hall of Fame') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active"
                                href="{{ url()->current() }}">{{ trans('hall-of-fame.Submit a Vulnerability') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <h1 class="text-center mb-5">{{ trans('hall-of-fame.Submit a Vulnerability') }}</h1>

                    <div class="card">
                        <div class="card-body">
                            <div class="alert alert-info">
                                <h4>{{ trans('hall-of-fame.Submission Guidelines') }}</h4>
                                <ul>
                                    <li>{{ trans('hall-of-fame.Provide detailed information about the vulnerability, including steps to reproduce') }}
                                    </li>
                                    <li>{{ trans('hall-of-fame.Do not include any sensitive personal data of users or employees') }}
                                    </li>
                                    <li>{{ trans('hall-of-fame.Never attempt to access, modify, or delete data without permission') }}
                                    </li>
                                    <li>{{ trans('hall-of-fame.Include screenshots or videos if they help explain the issue') }}
                                    </li>
                                    <li>{{ trans('hall-of-fame.Be respectful and patient as we investigate your report') }}
                                    </li>
                                    <li>{!! trans(
                                        'hall-of-fame.For sensitive reports, consider <a href=":url" target="_blank">using our PGP key</a> to encrypt your submission',
                                        ['url' => route('public.pgp-key')],
                                    ) !!}</li>
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

                            @if (Auth::check())
                                {!! Form::open([
                                    'route' => 'public.vulnerability-reports.store',
                                    'method' => 'POST',
                                    'files' => true,
                                    'class' => 'vulnerability-report-form',
                                ]) !!}

                                {!! $form->renderForm() !!}

                                {!! Form::close() !!}
                            @else
                                <div class="alert alert-warning text-center">
                                    <h4>{{ trans('hall-of-fame.Login Required') }}</h4>
                                    <p>{{ trans('hall-of-fame.You need to be logged in to submit a vulnerability report') }}
                                    </p>
                                    <div class="mt-4">
                                        <a href="/hall-of-fame/auth/login"
                                            class="btn btn-primary mx-2">{{ trans('hall-of-fame.Login') }}</a>
                                        <a href="/hall-of-fame/auth/register"
                                            class="btn btn-outline-primary mx-2">{{ trans('hall-of-fame.Register') }}</a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
</body>

</html>
