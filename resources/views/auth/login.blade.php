@include('plugins/hall-of-fame::partials.hof-navigation')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0 text-center">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        {{ trans('plugins/hall-of-fame::researcher.login_title') }}
                    </h3>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('public.hall-of-fame.auth.login.post') }}">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-1"></i>
                                {{ trans('plugins/hall-of-fame::researcher.email') }}
                            </label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-1"></i>
                                {{ trans('plugins/hall-of-fame::researcher.password') }}
                            </label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                {{ trans('plugins/hall-of-fame::researcher.login_button') }}
                            </button>
                        </div>
                    </form>

                    <div class="text-center">
                        <p class="text-muted">
                            {{ trans('plugins/hall-of-fame::researcher.no_account') }}
                            <a href="{{ route('public.hall-of-fame.auth.register') }}" class="text-decoration-none">
                                {{ trans('plugins/hall-of-fame::researcher.register_link') }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
