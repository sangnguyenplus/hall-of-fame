@include('plugins/hall-of-fame::partials.hof-navigation')

<div class="dashboard-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3 mb-1">{{ trans('plugins/hall-of-fame::dashboard.profile') }}</h1>
            <p class="text-muted mb-0">{{ trans('plugins/hall-of-fame::dashboard.edit_profile') }}</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="dashboard-card">
            <div class="card-header">
                <h5 class="mb-0">{{ trans('plugins/hall-of-fame::dashboard.personal_information') }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('public.hall-of-fame.dashboard.profile.update') }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                <label for="name">{{ trans('plugins/hall-of-fame::auth.name') }}</label>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                <label for="email">{{ trans('plugins/hall-of-fame::auth.email') }}</label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-floating">
                            <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" name="bio" style="height: 120px;"
                                placeholder="{{ trans('plugins/hall-of-fame::dashboard.bio') }}">{{ old('bio', $user->researcher->bio ?? '') }}</textarea>
                            <label for="bio">{{ trans('plugins/hall-of-fame::dashboard.bio') }}</label>
                            <div class="form-text">Tell the community about yourself, your skills, and experience.</div>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <h6 class="mb-3">{{ trans('plugins/hall-of-fame::dashboard.social_links') }}</h6>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="url" class="form-control @error('website') is-invalid @enderror"
                                    id="website" name="website"
                                    value="{{ old('website', $user->researcher->website ?? '') }}"
                                    placeholder="https://yourwebsite.com">
                                <label for="website">{{ trans('plugins/hall-of-fame::dashboard.website') }}</label>
                                @error('website')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control @error('twitter') is-invalid @enderror"
                                    id="twitter" name="twitter"
                                    value="{{ old('twitter', $user->researcher->twitter ?? '') }}"
                                    placeholder="yourusername">
                                <label for="twitter">{{ trans('plugins/hall-of-fame::dashboard.twitter') }}</label>
                                <div class="form-text">Without @ symbol</div>
                                @error('twitter')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control @error('github') is-invalid @enderror"
                                    id="github" name="github"
                                    value="{{ old('github', $user->researcher->github ?? '') }}"
                                    placeholder="yourusername">
                                <label for="github">{{ trans('plugins/hall-of-fame::dashboard.github') }}</label>
                                <div class="form-text">GitHub username only</div>
                                @error('github')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <div class="form-floating">
                                <input type="text" class="form-control @error('linkedin') is-invalid @enderror"
                                    id="linkedin" name="linkedin"
                                    value="{{ old('linkedin', $user->researcher->linkedin ?? '') }}"
                                    placeholder="yourusername">
                                <label for="linkedin">{{ trans('plugins/hall-of-fame::dashboard.linkedin') }}</label>
                                <div class="form-text">LinkedIn username only</div>
                                @error('linkedin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('public.hall-of-fame.dashboard.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Back to Dashboard
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Profile Preview -->
        <div class="dashboard-card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Profile Preview</h6>
            </div>
            <div class="card-body text-center">
                <div class="researcher-avatar mb-3">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=80&background=667eea&color=fff"
                        alt="{{ $user->name }}" class="rounded-circle">
                </div>
                <h5>{{ $user->name }}</h5>
                <p class="text-muted">{{ $user->email }}</p>

                @if ($user->researcher && $user->researcher->bio)
                    <p class="small">{{ Str::limit($user->researcher->bio, 100) }}</p>
                @endif

                <div class="social-links mt-3">
                    @if ($user->researcher && $user->researcher->website)
                        <a href="{{ $user->researcher->website }}" target="_blank"
                            class="btn btn-sm btn-outline-primary me-1">
                            <i class="fas fa-globe"></i>
                        </a>
                    @endif
                    @if ($user->researcher && $user->researcher->twitter)
                        <a href="https://twitter.com/{{ $user->researcher->twitter }}" target="_blank"
                            class="btn btn-sm btn-outline-info me-1">
                            <i class="fab fa-twitter"></i>
                        </a>
                    @endif
                    @if ($user->researcher && $user->researcher->github)
                        <a href="https://github.com/{{ $user->researcher->github }}" target="_blank"
                            class="btn btn-sm btn-outline-dark me-1">
                            <i class="fab fa-github"></i>
                        </a>
                    @endif
                    @if ($user->researcher && $user->researcher->linkedin)
                        <a href="https://linkedin.com/in/{{ $user->researcher->linkedin }}" target="_blank"
                            class="btn btn-sm btn-outline-primary">
                            <i class="fab fa-linkedin"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="dashboard-card">
            <div class="card-header">
                <h6 class="mb-0">Your Statistics</h6>
            </div>
            <div class="card-body">
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

                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <span class="text-muted">Reports Submitted</span>
                    <span class="fw-bold">{{ $totalReports }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <span class="text-muted">Reports Published</span>
                    <span class="fw-bold text-success">{{ $publishedReports }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center py-2">
                    <span class="text-muted">Certificates Earned</span>
                    <span class="fw-bold text-primary">{{ $certificates }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
