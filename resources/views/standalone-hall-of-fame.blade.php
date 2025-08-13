<!DOCTYPE html>
<html>

<head>
    <title>{{ trans('plugins/hall-of-fame::hall-of-fame.hall_of_fame') }}</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            padding: 20px;
            background-color: #f8f9fa;
        }

        .card {
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
            padding: 10px 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>{{ trans('plugins/hall-of-fame::hall-of-fame.hall_of_fame') }}</h1>
            <p class="lead">{{ trans('plugins/hall-of-fame::hall-of-fame.hall_of_fame_description') }}</p>
            <a href="/"
                class="btn btn-primary mb-4">{{ trans('plugins/hall-of-fame::hall-of-fame.back_to_home') }}</a>
        </div>

        <div class="row">
            @if (isset($reports) && $reports->count() > 0)
                @foreach ($reports as $report)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title">{{ $report->title }}</h5>
                                <p class="card-text">
                                    <strong>{{ trans('plugins/hall-of-fame::hall-of-fame.researcher') }}:</strong>
                                    {{ $report->researcher_name ?? ($report->researcher->name ?? trans('plugins/hall-of-fame::hall-of-fame.anonymous')) }}
                                </p>
                                <p class="card-text">
                                    <small class="text-muted">
                                        {{ $report->created_at->format('M d, Y') }}
                                    </small>
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="d-flex justify-content-center mt-4">
                    {{ $reports->links() }}
                </div>
            @else
                <div class="col-12">
                    <div class="alert alert-info">
                        No reports found.
                    </div>
                </div>
            @endif
        </div>

        <div class="text-center mt-5 mb-5">
            <a href="{{ route('public.vulnerability-reports.create') }}" class="btn btn-primary">
                Submit a Vulnerability
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
