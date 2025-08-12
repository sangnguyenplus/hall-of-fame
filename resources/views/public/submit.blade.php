<style>
    .vulnerability-form-container {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .form-step {
        background: white;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        border-left: 4px solid #667eea;
    }

    .step-header {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        color: #667eea;
        font-weight: 600;
    }

    .step-number {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #667eea;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-weight: bold;
    }

    .required {
        color: #dc3545;
    }

    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        padding: 15px 40px;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    }

    .guidelines-card {
        background: linear-gradient(135deg, #17a2b8, #20c997);
        color: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .guidelines-card h4 {
        color: white;
        margin-bottom: 1rem;
    }

    .guidelines-card ul li {
        margin-bottom: 0.5rem;
        padding-left: 0.5rem;
    }

    .auth-required-card {
        background: linear-gradient(135deg, #ffc107, #fd7e14);
        color: white;
        border-radius: 15px;
        padding: 2rem;
        text-align: center;
    }

    .auth-required-card h4 {
        color: white;
    }
</style>

@include('plugins/hall-of-fame::partials.hof-navigation')

<div class="text-center mb-4">
    <h1 class="display-4 fw-bold text-primary mb-3">
        <i class="fas fa-bug me-3"></i>
        {{ trans('plugins/hall-of-fame::hall-of-fame.submit_vulnerability') }}
    </h1>
    <p class="lead text-muted">Help us improve security by reporting vulnerabilities responsibly</p>
</div>

@if (Auth::check())
    <div class="vulnerability-form-container">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Guidelines Section -->
        <div class="guidelines-card">
            <h4><i
                    class="fas fa-shield-alt me-2"></i>{{ trans('plugins/hall-of-fame::vulnerability-reports.submission_guidelines') }}
            </h4>
            <ul class="list-unstyled">
                <li><i
                        class="fas fa-check-circle me-2"></i>{{ trans('plugins/hall-of-fame::vulnerability-reports.guideline_1') }}
                </li>
                <li><i
                        class="fas fa-check-circle me-2"></i>{{ trans('plugins/hall-of-fame::vulnerability-reports.guideline_2') }}
                </li>
                <li><i
                        class="fas fa-check-circle me-2"></i>{{ trans('plugins/hall-of-fame::vulnerability-reports.guideline_3') }}
                </li>
                <li><i
                        class="fas fa-check-circle me-2"></i>{{ trans('plugins/hall-of-fame::vulnerability-reports.guideline_4') }}
                </li>
                <li><i
                        class="fas fa-check-circle me-2"></i>{{ trans('plugins/hall-of-fame::vulnerability-reports.guideline_5') }}
                </li>
                <li><i class="fas fa-key me-2"></i>{!! trans('plugins/hall-of-fame::vulnerability-reports.guideline_pgp', ['url' => route('public.pgp-key')]) !!}</li>
            </ul>
        </div>

        {!! Form::open([
            'route' => 'public.vulnerability-reports.store',
            'method' => 'POST',
            'files' => true,
            'class' => 'vulnerability-report-form',
        ]) !!}

        <!-- Step 1: Basic Information -->
        <div class="form-step">
            <div class="step-header">
                <div class="step-number">1</div>
                <h5 class="mb-0">Basic Information</h5>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="title" class="form-label">
                        <i class="fas fa-heading me-1"></i>
                        {{ trans('plugins/hall-of-fame::hall-of-fame.form_title') }}
                        <span class="required">*</span>
                    </label>
                    <input type="text" class="form-control" id="title" name="title"
                        placeholder="{{ trans('plugins/hall-of-fame::hall-of-fame.form_title_placeholder') }}"
                        value="{{ old('title') }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="vulnerability_type" class="form-label">
                        <i class="fas fa-tag me-1"></i>
                        {{ trans('plugins/hall-of-fame::hall-of-fame.form_vulnerability_type') }}
                        <span class="required">*</span>
                    </label>
                    <select class="form-control" id="vulnerability_type" name="vulnerability_type" required>
                        <option value="">Select vulnerability type...</option>
                        <option value="XSS" {{ old('vulnerability_type') == 'XSS' ? 'selected' : '' }}>Cross-Site
                            Scripting (XSS)</option>
                        <option value="SQLi" {{ old('vulnerability_type') == 'SQLi' ? 'selected' : '' }}>SQL
                            Injection</option>
                        <option value="CSRF" {{ old('vulnerability_type') == 'CSRF' ? 'selected' : '' }}>Cross-Site
                            Request Forgery (CSRF)</option>
                        <option value="LFI" {{ old('vulnerability_type') == 'LFI' ? 'selected' : '' }}>Local File
                            Inclusion (LFI)</option>
                        <option value="RFI" {{ old('vulnerability_type') == 'RFI' ? 'selected' : '' }}>Remote File
                            Inclusion (RFI)</option>
                        <option value="RCE" {{ old('vulnerability_type') == 'RCE' ? 'selected' : '' }}>Remote Code
                            Execution (RCE)</option>
                        <option value="IDOR" {{ old('vulnerability_type') == 'IDOR' ? 'selected' : '' }}>Insecure
                            Direct Object Reference (IDOR)</option>
                        <option value="Privilege Escalation"
                            {{ old('vulnerability_type') == 'Privilege Escalation' ? 'selected' : '' }}>Privilege
                            Escalation</option>
                        <option value="Information Disclosure"
                            {{ old('vulnerability_type') == 'Information Disclosure' ? 'selected' : '' }}>Information
                            Disclosure</option>
                        <option value="SSRF" {{ old('vulnerability_type') == 'SSRF' ? 'selected' : '' }}>Server-Side
                            Request Forgery (SSRF)</option>
                        <option value="Other" {{ old('vulnerability_type') == 'Other' ? 'selected' : '' }}>Other
                        </option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="endpoint" class="form-label">
                    <i class="fas fa-link me-1"></i>
                    {{ trans('plugins/hall-of-fame::hall-of-fame.form_endpoint') }}
                </label>
                <input type="text" class="form-control" id="endpoint" name="endpoint"
                    placeholder="{{ trans('plugins/hall-of-fame::hall-of-fame.form_endpoint_placeholder') }}"
                    value="{{ old('endpoint') }}">
            </div>
        </div>

        <!-- Step 2: Technical Details -->
        <div class="form-step">
            <div class="step-header">
                <div class="step-number">2</div>
                <h5 class="mb-0">Technical Details</h5>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">
                    <i class="fas fa-file-alt me-1"></i>
                    {{ trans('plugins/hall-of-fame::hall-of-fame.form_description') }}
                    <span class="required">*</span>
                </label>
                <textarea class="form-control" id="description" name="description" rows="5"
                    placeholder="{{ trans('plugins/hall-of-fame::hall-of-fame.form_description_placeholder') }}" required>{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="impact" class="form-label">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    {{ trans('plugins/hall-of-fame::hall-of-fame.form_impact') }}
                    <span class="required">*</span>
                </label>
                <textarea class="form-control" id="impact" name="impact" rows="4"
                    placeholder="{{ trans('plugins/hall-of-fame::hall-of-fame.form_impact_placeholder') }}" required>{{ old('impact') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="steps_to_reproduce" class="form-label">
                    <i class="fas fa-list-ol me-1"></i>
                    {{ trans('plugins/hall-of-fame::hall-of-fame.form_steps_to_reproduce') }}
                    <span class="required">*</span>
                </label>
                <textarea class="form-control" id="steps_to_reproduce" name="steps_to_reproduce" rows="6"
                    placeholder="{{ trans('plugins/hall-of-fame::hall-of-fame.form_steps_to_reproduce_placeholder') }}" required>{{ old('steps_to_reproduce') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="suggested_fix" class="form-label">
                    <i class="fas fa-tools me-1"></i>
                    {{ trans('plugins/hall-of-fame::hall-of-fame.form_suggested_fix') }}
                </label>
                <textarea class="form-control" id="suggested_fix" name="suggested_fix" rows="4"
                    placeholder="{{ trans('plugins/hall-of-fame::hall-of-fame.form_suggested_fix_placeholder') }}">{{ old('suggested_fix') }}</textarea>
            </div>
        </div>

        <!-- Step 3: Researcher Information -->
        <div class="form-step">
            <div class="step-header">
                <div class="step-number">3</div>
                <h5 class="mb-0">Researcher Information</h5>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="researcher_name" class="form-label">
                        <i class="fas fa-user me-1"></i>
                        {{ trans('plugins/hall-of-fame::hall-of-fame.form_researcher_name') }}
                        <span class="required">*</span>
                    </label>
                    <input type="text" class="form-control" id="researcher_name" name="researcher_name"
                        placeholder="{{ trans('plugins/hall-of-fame::hall-of-fame.form_researcher_name_placeholder') }}"
                        value="{{ old('researcher_name', Auth::user()->name ?? '') }}" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="researcher_email" class="form-label">
                        <i class="fas fa-envelope me-1"></i>
                        {{ trans('plugins/hall-of-fame::hall-of-fame.form_researcher_email') }}
                        <span class="required">*</span>
                    </label>
                    <input type="email" class="form-control" id="researcher_email" name="researcher_email"
                        placeholder="{{ trans('plugins/hall-of-fame::hall-of-fame.form_researcher_email_placeholder') }}"
                        value="{{ old('researcher_email', Auth::user()->email ?? '') }}" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="researcher_bio" class="form-label">
                    <i class="fas fa-info-circle me-1"></i>
                    {{ trans('plugins/hall-of-fame::hall-of-fame.form_researcher_bio') }}
                </label>
                <textarea class="form-control" id="researcher_bio" name="researcher_bio" rows="3"
                    placeholder="{{ trans('plugins/hall-of-fame::hall-of-fame.form_researcher_bio_placeholder') }}">{{ old('researcher_bio') }}</textarea>
            </div>
        </div>

        <!-- Step 4: Attachments & Final -->
        <div class="form-step">
            <div class="step-header">
                <div class="step-number">4</div>
                <h5 class="mb-0">Attachments & Submission</h5>
            </div>

            <div class="mb-3">
                <label for="attachments" class="form-label">
                    <i class="fas fa-paperclip me-1"></i>
                    {{ trans('plugins/hall-of-fame::hall-of-fame.form_attachments') }}
                </label>
                <input type="file" class="form-control" id="attachments" name="attachments[]" multiple>
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    {{ trans('plugins/hall-of-fame::hall-of-fame.form_attachments_hint') }}
                </small>
            </div>

            <div class="mb-4">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="privacy_policy" name="privacy_policy"
                        required>
                    <label class="form-check-label" for="privacy_policy">
                        <i class="fas fa-shield-alt me-1"></i>
                        {{ trans('plugins/hall-of-fame::hall-of-fame.form_privacy_policy') }}
                        <span class="required">*</span>
                    </label>
                </div>
            </div>

            <!-- reCaptcha -->
            @if (class_exists('Anhskohbo\NoCaptcha\Facades\NoCaptcha'))
                <div class="mb-4 text-center">
                    {!! Captcha::display() !!}
                </div>
            @else
                <div class="mb-4 text-center">
                    <div class="alert alert-info">
                        <i class="fas fa-robot me-2"></i>
                        reCAPTCHA validation will be applied during submission.
                    </div>
                </div>
            @endif
            <div class="text-center">
                <button type="submit" class="btn btn-submit btn-lg">
                    <i class="fas fa-paper-plane me-2"></i>
                    {{ trans('plugins/hall-of-fame::hall-of-fame.form_submit') }}
                </button>
            </div>
        </div>

        {!! Form::close() !!}
    </div>
@else
    <!-- Authentication Required Section -->
    <div class="auth-required-card">
        <i class="fas fa-lock display-1 mb-4"></i>
        <h4>{{ trans('plugins/hall-of-fame::hall-of-fame.Login Required') }}</h4>
        <p class="mb-4">
            {{ trans('plugins/hall-of-fame::hall-of-fame.You need to be logged in to submit a vulnerability report') }}
        </p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('public.hall-of-fame.auth.login') }}" class="btn btn-light btn-lg">
                <i class="fas fa-sign-in-alt me-2"></i>
                {{ trans('plugins/hall-of-fame::hall-of-fame.Login') }}
            </a>
            <a href="{{ route('public.hall-of-fame.auth.register') }}" class="btn btn-outline-light btn-lg">
                <i class="fas fa-user-plus me-2"></i>
                {{ trans('plugins/hall-of-fame::hall-of-fame.Register') }}
            </a>
        </div>
    </div>
@endif
