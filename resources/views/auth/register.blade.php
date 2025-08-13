@include('plugins/hall-of-fame::partials.hof-master')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="hof-card hof-auth-card">
                <div class="hof-card-header">
                    <h3 class="hof-card-title text-center">
                        <i class="fas fa-user-plus me-2"></i>
                        {{ trans('plugins/hall-of-fame::auth.register') }}
                    </h3>
                </div>
                <div class="hof-card-content">
                    <form method="POST" action="{{ route('public.hall-of-fame.auth.register.post') }}">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="hof-form-group">
                                    <label for="first_name" class="hof-form-label">
                                        <i class="fas fa-user me-1"></i>
                                        {{ trans('plugins/hall-of-fame::auth.first_name') }}
                                    </label>
                                    <input type="text"
                                        class="hof-form-control @error('first_name') is-invalid @enderror"
                                        id="first_name" name="first_name" value="{{ old('first_name') }}" required
                                        autofocus>
                                    @error('first_name')
                                        <div class="hof-form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="hof-form-group">
                                    <label for="last_name" class="hof-form-label">
                                        <i class="fas fa-user me-1"></i>
                                        {{ trans('plugins/hall-of-fame::auth.last_name') }}
                                    </label>
                                    <input type="text"
                                        class="hof-form-control @error('last_name') is-invalid @enderror" id="last_name"
                                        name="last_name" value="{{ old('last_name') }}" required>
                                    @error('last_name')
                                        <div class="hof-form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="hof-form-group">
                            <label for="email" class="hof-form-label">
                                <i class="fas fa-envelope me-1"></i>
                                {{ trans('plugins/hall-of-fame::auth.email') }}
                            </label>
                            <input type="email" class="hof-form-control @error('email') is-invalid @enderror"
                                id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="hof-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="hof-form-group">
                                    <label for="password" class="hof-form-label">
                                        <i class="fas fa-lock me-1"></i>
                                        {{ trans('plugins/hall-of-fame::auth.password') }}
                                    </label>
                                    <input type="password"
                                        class="hof-form-control @error('password') is-invalid @enderror" id="password"
                                        name="password" required>
                                    @error('password')
                                        <div class="hof-form-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="hof-form-group">
                                    <label for="password_confirmation" class="hof-form-label">
                                        <i class="fas fa-lock me-1"></i>
                                        {{ trans('plugins/hall-of-fame::auth.confirm_password') }}
                                    </label>
                                    <input type="password" class="hof-form-control" id="password_confirmation"
                                        name="password_confirmation" required>
                                </div>
                            </div>
                        </div>

                        <div class="hof-form-submit">
                            <button type="submit" class="hof-btn hof-btn-success hof-btn-lg">
                                <i class="fas fa-user-plus me-2"></i>
                                {{ trans('plugins/hall-of-fame::auth.register') }}
                            </button>
                        </div>
                    </form>

                    <div class="hof-auth-footer">
                        <p class="hof-text-muted">
                            {{ trans('plugins/hall-of-fame::auth.already_account') }}
                            <a href="{{ route('public.hall-of-fame.auth.login') }}" class="hof-link">
                                {{ trans('plugins/hall-of-fame::auth.login') }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
