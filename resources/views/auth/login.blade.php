@extends('plugins/hall-of-fame::layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card my-5">
                    <div class="card-header">
                        <h3 class="mb-0">{{ trans('plugins/hall-of-fame::researcher.login_title') }}</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('researchers.login.post') }}">
                            @csrf

                            <div class="form-group mb-3">
                                <label for="email">{{ trans('plugins/hall-of-fame::researcher.email') }}</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}" required autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="password">{{ trans('plugins/hall-of-fame::researcher.password') }}</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-0 d-grid">
                                <button type="submit" class="btn btn-primary">
                                    {{ trans('plugins/hall-of-fame::researcher.login_button') }}
                                </button>
                            </div>

                            <div class="text-center mt-3">
                                <p>{{ trans('plugins/hall-of-fame::researcher.no_account') }} <a
                                        href="{{ route('researchers.register') }}">{{ trans('plugins/hall-of-fame::researcher.register_link') }}</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
