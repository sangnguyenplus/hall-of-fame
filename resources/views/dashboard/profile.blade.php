@include('plugins/hall-of-fame::partials.hof-master')

<div class="hof-container">
    <div class="hof-dashboard-header hof-animate-slide-in">
        <div class="hof-flex-between">
            <div>
                <h1 c <div class="hof-form-group">
                    <label for="password_confirmation" class="hof-form-label">
                        <i class="fas fa-check me-2"></i>Confirm New Password
                    </label>
                    <input type="password" class="hof-form-control" id="password_confirmation" name="password_confirmation"
                        required>
            </div>

            <div class="hof-card-actions hof-mt-4">
                <button type="submit" class="hof-btn hof-btn-warning hof-animate-hover">
                    <i class="fas fa-shield-alt me-2"></i>
                    Update Password
                </button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="hof-grid-sidebar">
    <!-- Profile Preview -->
    <div class="hof-card hof-card-modern hof-animate-fade-in" style="animation-delay: 0.3s">
        <div class="hof-card-header">
            <h4 class="hof-heading-card">Profile Preview</h4>
        </div>
        <div class="hof-card-content hof-text-center">&imary hof-gradient-text">
            <i class="fas fa-user-circle me-3"></i>
            {{ trans('plugins/hall-of-fame::dashboard.profile') }}
            </h1>
            <p class="hof-text-secondary">{{ trans('plugins/hall-of-fame::dashboard.edit_profile') }}</p>
        </div>
        <div>
            <a href="{{ route('public.hall-of-fame.dashboard.index') }}"
                class="hof-btn hof-btn-secondary hof-animate-hover">
                <i class="fas fa-arrow-left me-2"></i>
                {{ trans('plugins/hall-of-fame::dashboard.back_to_dashboard') }}
            </a>
        </div>
    </div>
</div>

<div class="hof-grid-layout">
    <div class="hof-grid-main">
        <!-- Personal Information Card -->
        <div class="hof-card hof-card-modern hof-animate-fade-in" style="animation-delay: 0.1s">
            <div class="hof-card-header">
                <div class="hof-flex-center">
                    <div class="hof-icon-circle hof-icon-lg hof-gradient-primary me-3">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div>
                        <h3 class="hof-heading-card">{{ trans('plugins/hall-of-fame::dashboard.personal_information') }}
                        </h3>
                        <p class="hof-text-secondary hof-mb-0">Manage your basic account information</p>
                    </div>
                </div>
            </div>
            <div class="hof-card-content">
                <form method="POST" action="{{ route('public.hall-of-fame.dashboard.profile.update') }}"
                    class="hof-form-modern">
                    @csrf

                    <div class="hof-form-grid">
                        <div class="hof-form-group">
                            <label for="name"
                                class="hof-form-label">{{ trans('plugins/hall-of-fame::auth.name') }}</label>
                            <input type="text" class="hof-form-control @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="hof-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="hof-form-group">
                            <label for="email"
                                class="hof-form-label">{{ trans('plugins/hall-of-fame::auth.email') }}</label>
                            <input type="email" class="hof-form-control @error('email') is-invalid @enderror"
                                id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="hof-form-error">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="hof-form-group">
                        <label for="bio"
                            class="hof-form-label">{{ trans('plugins/hall-of-fame::dashboard.bio') }}</label>
                        <textarea class="hof-form-control @error('bio') is-invalid @enderror" id="bio" name="bio" rows="5"
                            placeholder="{{ trans('plugins/hall-of-fame::dashboard.bio') }}">{{ old('bio', $user->researcher->bio ?? '') }}</textarea>
                        <div class="hof-form-help">Tell the community about yourself, your skills, and experience.</div>
                        @error('bio')
                            <div class="hof-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="hof-section-divider">
                        <div class="hof-flex-center">
                            <div class="hof-icon-circle hof-icon-md hof-gradient-secondary me-3">
                                <i class="fas fa-link text-white"></i>
                            </div>
                            <div>
                                <h4 class="hof-section-title">
                                    {{ trans('plugins/hall-of-fame::dashboard.social_links') }}</h4>
                                <p class="hof-text-secondary hof-mb-0">Connect your social media profiles</p>
                            </div>
                        </div>
                    </div>

                    <div class="hof-form-grid">
                        <div class="hof-form-group">
                            <label for="website" class="hof-form-label">
                                <i class="fas fa-globe me-2"></i>{{ trans('plugins/hall-of-fame::dashboard.website') }}
                            </label>
                            <input type="url" <input type="url"
                                class="hof-form-control @error('website') is-invalid @enderror" id="website"
                                name="website" value="{{ old('website', $user->researcher->website ?? '') }}"
                                placeholder="https://yourwebsite.com">
                            @error('website')
                                <div class="hof-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="hof-form-group">
                            <label for="twitter" class="hof-form-label">
                                <i
                                    class="fab fa-twitter me-2"></i>{{ trans('plugins/hall-of-fame::dashboard.twitter') }}
                            </label>
                            <div class="hof-input-group">
                                <span class="hof-input-prefix">@</span>
                                <input type="text" class="hof-form-control @error('twitter') is-invalid @enderror"
                                    id="twitter" name="twitter"
                                    value="{{ old('twitter', $user->researcher->twitter ?? '') }}"
                                    placeholder="yourusername">
                            </div>
                            @error('twitter')
                                <div class="hof-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="hof-form-group">
                            <label for="github" class="hof-form-label">
                                <i
                                    class="fab fa-github me-2"></i>{{ trans('plugins/hall-of-fame::dashboard.github') }}
                            </label>
                            <div class="hof-input-group">
                                <span class="hof-input-prefix">github.com/</span>
                                <input type="text" class="hof-form-control @error('github') is-invalid @enderror"
                                    id="github" name="github"
                                    value="{{ old('github', $user->researcher->github ?? '') }}"
                                    placeholder="yourusername">
                            </div>
                            @error('github')
                                <div class="hof-form-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="hof-form-group">

                            <div class="hof-form-group">
                                <label for="linkedin" class="hof-form-label">
                                    <i
                                        class="fab fa-linkedin me-2"></i>{{ trans('plugins/hall-of-fame::dashboard.linkedin') }}
                                </label>
                                <div class="hof-input-group">
                                    <span class="hof-input-prefix">linkedin.com/in/</span>
                                    <input type="text"
                                        class="hof-form-control @error('linkedin') is-invalid @enderror"
                                        id="linkedin" name="linkedin"
                                        value="{{ old('linkedin', $user->researcher->linkedin ?? '') }}"
                                        placeholder="yourusername">
                                </div>
                                @error('linkedin')
                                    <div class="hof-form-error">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="hof-card-actions hof-mt-6">
                            <button type="submit" class="hof-btn hof-btn-primary hof-btn-lg hof-animate-hover">
                                <i class="fas fa-save me-2"></i>
                                Update Profile
                            </button>
                            <a href="{{ route('public.hall-of-fame.dashboard.index') }}"
                                class="hof-btn hof-btn-secondary hof-animate-hover">
                                <i class="fas fa-arrow-left me-2"></i>
                                Back to Dashboard
                            </a>
                        </div>
                </form>
            </div>
        </div>

        <!-- Password Change Section -->
        <div class="hof-card hof-card-modern hof-animate-fade-in" style="animation-delay: 0.2s">
            <div class="hof-card-header">
                <div class="hof-flex-center">
                    <div class="hof-icon-circle hof-icon-lg hof-gradient-warning me-3">
                        <i class="fas fa-lock text-white"></i>
                    </div>
                    <div>
                        <h3 class="hof-heading-card">Change Password</h3>
                        <p class="hof-text-secondary hof-mb-0">Update your account password</p>
                    </div>
                </div>
            </div>
            <div class="hof-card-content">
                <form method="POST" action="{{ route('public.hall-of-fame.dashboard.profile.update') }}">
                    @csrf

                    <div class="hof-form-group mb-3">
                        <label for="current_password" class="hof-form-label">
                            <i class="fas fa-key me-2"></i>Current Password
                        </label>
                        <input type="password"
                            class="hof-form-control @error('current_password') is-invalid @enderror"
                            id="current_password" name="current_password" required>
                        @error('current_password')
                            <div class="hof-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="hof-form-group mb-3">
                        <label for="password" class="hof-form-label">
                            <i class="fas fa-lock me-2"></i>New Password
                        </label>
                        <input type="password" class="hof-form-control @error('password') is-invalid @enderror"
                            id="password" name="password" required>
                        @error('password')
                            <div class="hof-form-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="hof-form-group mb-3">
                        <label for="password_confirmation" class="hof-form-label">
                            <i class="fas fa-check me-2"></i>Confirm New Password
                        </label>
                        <input type="password" class="hof-form-control" id="password_confirmation"
                            name="password_confirmation" required>
                    </div>

                    <div class="hof-card-footer">
                        <button type="submit" class="hof-btn hof-btn-warning">
                            <i class="fas fa-shield-alt me-2"></i>
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Profile Preview -->
        <div class="hof-card mb-4">
            <div class="hof-card-header">
                <h6 class="hof-card-title mb-0">Profile Preview</h6>
            </div>
            <div class="hof-card-content hof-text-center">
                <div class="hof-avatar-wrapper hof-mb-4">
                    <div class="hof-avatar hof-avatar-xl hof-gradient-primary">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=120&background=667eea&color=fff"
                            alt="{{ $user->name }}" class="hof-avatar-img">
                    </div>
                </div>
                <h4 class="hof-heading-card hof-mb-1">{{ $user->name }}</h4>
                <p class="hof-text-secondary hof-mb-3">{{ $user->email }}</p>

                @if ($user->researcher && $user->researcher->bio)
                    <p class="hof-text-small hof-mb-4">{{ Str::limit($user->researcher->bio, 100) }}</p>
                @endif

                <div class="hof-social-links">
                    @if ($user->researcher && $user->researcher->website)
                        <a href="{{ $user->researcher->website }}" target="_blank"
                            class="hof-btn hof-btn-outline hof-btn-sm hof-animate-hover">
                            <i class="fas fa-globe"></i>
                        </a>
                    @endif
                    @if ($user->researcher && $user->researcher->twitter)
                        <a href="https://twitter.com/{{ $user->researcher->twitter }}" target="_blank"
                            class="hof-btn hof-btn-outline hof-btn-sm hof-animate-hover">
                            <i class="fab fa-twitter"></i>
                        </a>
                    @endif
                    @if ($user->researcher && $user->researcher->github)
                        <a href="https://github.com/{{ $user->researcher->github }}" target="_blank"
                            class="hof-btn hof-btn-outline hof-btn-sm hof-animate-hover">
                            <i class="fab fa-github"></i>
                        </a>
                    @endif
                    @if ($user->researcher && $user->researcher->linkedin)
                        <a href="https://linkedin.com/in/{{ $user->researcher->linkedin }}" target="_blank"
                            class="hof-btn hof-btn-outline hof-btn-sm hof-animate-hover">
                            <i class="fab fa-linkedin"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="hof-card hof-card-modern hof-animate-fade-in" style="animation-delay: 0.4s">
            <div class="hof-card-header">
                <h4 class="hof-heading-card">Your Statistics</h4>
            </div>
            <div class="hof-card-content">
                @php
                    $totalReports = \Whozidis\HallOfFame\Models\VulnerabilityReport::where(
                        'user_id',
                        $user->id,
                    )->count();
                    $publishedReports = \Whozidis\HallOfFame\Models\VulnerabilityReport::where('user_id', $user->id)
                        ->where('status', 'published')
                        ->count();
                    $certificates = \Whozidis\HallOfFame\Models\Certificate::whereHas('vulnerabilityReport', function (
                        $q,
                    ) use ($user) {
                        $q->where('user_id', $user->id);
                    })->count();
                @endphp

                <div class="hof-stats-grid">
                    <div class="hof-stat-item">
                        <div class="hof-stat-icon hof-gradient-primary">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="hof-stat-content">
                            <div class="hof-stat-value">{{ $totalReports }}</div>
                            <div class="hof-stat-label">Reports Submitted</div>
                        </div>
                    </div>
                    <div class="hof-stat-item">
                        <div class="hof-stat-icon hof-gradient-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="hof-stat-content">
                            <div class="hof-stat-value hof-text-success">{{ $publishedReports }}</div>
                            <div class="hof-stat-label">Reports Published</div>
                        </div>
                    </div>
                    <div class="hof-stat-item">
                        <div class="hof-stat-icon hof-gradient-warning">
                            <i class="fas fa-certificate"></i>
                        </div>
                        <div class="hof-stat-content">
                            <div class="hof-stat-value hof-text-warning">{{ $certificates }}</div>
                            <div class="hof-stat-label">Certificates Earned</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
