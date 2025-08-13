@include('plugins/hall-of-fame::partials.hof-master')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="hof-card hof-auth-card">
                <div class="hof-card-header">
                    <h3 class="hof-card-title text-center">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        {{ trans('plugins/hall-of-fame::researcher.login_title') }}
                    </h3>
                </div>
                <div class="hof-card-content">
                    <form method="POST" action="{{ route('public.hall-of-fame.auth.login.post') }}">
                        @csrf

                        <div class="hof-form-group">
                            <label for="email" class="hof-form-label">
                                <i class="fas fa-envelope me-1"></i>
                                {{ trans('plugins/hall-of-fame::researcher.email') }}
                            </label>
                            <input type="email" class="hof-form-control @error('email') is-invalid @enderror"
                                id="email" name="email" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <div class="hof-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="hof-form-group">
                            <label for="password" class="hof-form-label">
                                <i class="fas fa-lock me-1"></i>
                                {{ trans('plugins/hall-of-fame::researcher.password') }}
                            </label>
                            <input type="password" class="hof-form-control @error('password') is-invalid @enderror"
                                id="password" name="password" required>
                            @error('password')
                                <div class="hof-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="hof-form-check">
                            <input class="hof-form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="hof-form-check-label" for="remember">
                                {{ trans('plugins/hall-of-fame::auth.remember_me') }}
                            </label>
                        </div>

                        <div class="hof-form-submit">
                            <button type="submit" class="hof-btn hof-btn-primary hof-btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                {{ trans('plugins/hall-of-fame::researcher.login_button') }}
                            </button>
                        </div>
                    </form>

                    <div class="hof-auth-footer">
                        <p class="hof-text-muted">
                            {{ trans('plugins/hall-of-fame::researcher.no_account') }}
                            <a href="{{ route('public.hall-of-fame.auth.register') }}" class="hof-link">
                                {{ trans('plugins/hall-of-fame::researcher.register_link') }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
