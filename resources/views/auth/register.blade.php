@extends('plugins/hall-of-fame::layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card my-5">
                    <div class="card-header">
                        <h3 class="mb-0">{{ trans('plugins/hall-of-fame::auth.register') }}</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('public.hall-of-fame.auth.register.post') }}">
                            @csrf

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="first_name">{{ trans('plugins/hall-of-fame::auth.first_name') }}</label>
                                        <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                            id="first_name" name="first_name" value="{{ old('first_name') }}" required
                                            autofocus>
                                        @error('first_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="last_name">{{ trans('plugins/hall-of-fame::auth.last_name') }}</label>
                                        <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                            id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                        @error('last_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="email">{{ trans('plugins/hall-of-fame::auth.email') }}</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="password">{{ trans('plugins/hall-of-fame::auth.password') }}</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-4">
                                <label
                                    for="password_confirmation">{{ trans('plugins/hall-of-fame::auth.confirm_password') }}</label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" required>
                            </div>

                            <div class="form-group mb-0 d-grid">
                                <button type="submit" class="btn btn-primary">
                                    {{ trans('plugins/hall-of-fame::auth.register') }}
                                </button>
                            </div>

                            <div class="text-center mt-3">
                                <p>{{ trans('plugins/hall-of-fame::auth.already_account') }} <a
                                        href="{{ route('public.hall-of-fame.auth.login') }}">{{ trans('plugins/hall-of-fame::auth.login') }}</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
