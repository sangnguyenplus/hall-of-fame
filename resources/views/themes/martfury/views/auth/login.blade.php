@extends('plugins/hall-of-fame::themes.martfury.layouts.master')

@section('content')
    <div class="ps-my-account">
        <div class="container">
            <div class="ps-form--account ps-tab-root">
                <div class="ps-tab__panel">
                    <h4 class="mb-4">{{ trans('plugins/hall-of-fame::researcher.login_title') }}</h4>

                    {!! Form::open(['route' => 'researchers.login.post', 'class' => 'ps-form__content']) !!}
                    {!! Form::token() !!}

                    <div class="form-group">
                        <label for="email" class="mb-1">{{ trans('plugins/hall-of-fame::researcher.email') }}</label>
                        {!! Form::email('email', old('email'), [
                            'class' => 'form-control' . ($errors->has('email') ? ' is-invalid' : ''),
                            'required' => true,
                            'autofocus' => true,
                            'placeholder' => trans('plugins/hall-of-fame::researcher.email_placeholder'),
                        ]) !!}
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password"
                            class="mb-1">{{ trans('plugins/hall-of-fame::researcher.password') }}</label>
                        {!! Form::password('password', [
                            'class' => 'form-control' . ($errors->has('password') ? ' is-invalid' : ''),
                            'required' => true,
                            'placeholder' => trans('plugins/hall-of-fame::researcher.password_placeholder'),
                        ]) !!}
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button type="submit" class="ps-btn ps-btn--fullwidth">
                            {{ trans('plugins/hall-of-fame::researcher.login_button') }}
                        </button>
                    </div>

                    <div class="text-center mt-3">
                        <p>{{ trans('plugins/hall-of-fame::researcher.no_account') }}
                            <a href="{{ route('researchers.register') }}" class="text-primary">
                                {{ trans('plugins/hall-of-fame::researcher.register_link') }}
                            </a>
                        </p>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
