@include('plugins/hall-of-fame::partials.hof-navigation')

<div class="dashboard-header mb-4">
    <div class="row align-items-center">
        <div class="col">
            <h1 class="h3 mb-1">{{ trans('plugins/hall-of-fame::dashboard.certificates') }}</h1>
            <p class="text-muted mb-0">Manage and view your earned certificates</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('public.hall-of-fame.dashboard.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                Back to Dashboard
            </a>
        </div>
    </div>
</div>

@if ($certificates->count() > 0)
    <div class="row">
        @foreach ($certificates as $certificate)
            <div class="col-lg-6 mb-4">
                <div class="dashboard-card certificate-card position-relative">
                    <div class="certificate-badge certificate-verified">
                        <i class="fas fa-check"></i>
                    </div>

                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0 me-3">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 60px; height: 60px;">
                                    <i class="fas fa-certificate fa-2x"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-2">{{ $certificate->vulnerability_title }}</h5>
                                <p class="text-muted mb-1">
                                    <strong>{{ trans('plugins/hall-of-fame::dashboard.certificate_id') }}:</strong>
                                    {{ $certificate->certificate_id }}
                                </p>
                                <p class="text-muted mb-2">
                                    <strong>{{ trans('plugins/hall-of-fame::dashboard.vulnerability_type') }}:</strong>
                                    {{ $certificate->vulnerability_type }}
                                </p>
                                <p class="text-muted mb-3">
                                    <strong>{{ trans('plugins/hall-of-fame::dashboard.issued_date') }}:</strong>
                                    {{ $certificate->acknowledgment_date ? \Carbon\Carbon::parse($certificate->acknowledgment_date)->format('M d, Y') : 'N/A' }}
                                </p>

                                <div class="d-flex gap-2 flex-wrap">
                                    <a href="{{ route('public.certificates.view', $certificate->certificate_id) }}"
                                        class="btn btn-sm btn-primary" target="_blank">
                                        <i class="fas fa-eye me-1"></i>
                                        {{ trans('plugins/hall-of-fame::dashboard.view') }}
                                    </a>

                                    <a href="{{ route('public.certificates.download', $certificate->certificate_id) }}"
                                        class="btn btn-sm btn-success">
                                        <i class="fas fa-download me-1"></i>
                                        {{ trans('plugins/hall-of-fame::dashboard.download') }}
                                    </a>

                                    <a href="{{ route('public.certificates.verify', $certificate->certificate_id) }}"
                                        class="btn btn-sm btn-outline-info" target="_blank">
                                        <i class="fas fa-shield-alt me-1"></i>
                                        {{ trans('plugins/hall-of-fame::dashboard.verify') }}
                                    </a>

                                    <button class="btn btn-sm btn-outline-secondary"
                                        onclick="shareCertificate('{{ $certificate->certificate_id }}', '{{ $certificate->vulnerability_title }}')">
                                        <i class="fas fa-share me-1"></i>
                                        {{ trans('plugins/hall-of-fame::dashboard.share') }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        @if ($certificate->description)
                            <div class="mt-3 pt-3 border-top">
                                <h6 class="mb-2">{{ trans('plugins/hall-of-fame::dashboard.description') }}</h6>
                                <p class="text-muted small mb-0">{{ $certificate->description }}</p>
                            </div>
                        @endif

                        <!-- Certificate Status -->
                        <div class="mt-3 pt-3 border-top">
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="d-flex flex-column">
                                        <span class="text-{{ $certificate->is_signed ? 'success' : 'warning' }}">
                                            <i
                                                class="fas fa-{{ $certificate->is_signed ? 'check-circle' : 'clock' }} fa-lg"></i>
                                        </span>
                                        <small class="text-muted mt-1">
                                            {{ $certificate->is_signed ? 'Signed' : 'Pending' }}
                                        </small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="d-flex flex-column">
                                        <span class="text-{{ $certificate->is_encrypted ? 'success' : 'muted' }}">
                                            <i
                                                class="fas fa-{{ $certificate->is_encrypted ? 'lock' : 'unlock' }} fa-lg"></i>
                                        </span>
                                        <small class="text-muted mt-1">
                                            {{ $certificate->is_encrypted ? 'Encrypted' : 'Standard' }}
                                        </small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="d-flex flex-column">
                                        <span class="text-success">
                                            <i class="fas fa-certificate fa-lg"></i>
                                        </span>
                                        <small class="text-muted mt-1">
                                            Verified
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if ($certificates->hasPages())
        <div class="d-flex justify-content-center">
            {{ $certificates->links() }}
        </div>
    @endif
@else
    <!-- Empty State -->
    <div class="dashboard-card">
        <div class="card-body text-center py-5">
            <div class="mb-4">
                <i class="fas fa-certificate fa-4x text-muted opacity-50"></i>
            </div>
            <h4 class="text-muted mb-3">{{ trans('plugins/hall-of-fame::dashboard.no_certificates') }}</h4>
            <p class="text-muted mb-4">Submit vulnerability reports to earn your first certificate!</p>
            <a href="{{ route('public.vulnerability-reports.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>
                {{ trans('plugins/hall-of-fame::dashboard.submit_new_report') }}
            </a>
        </div>
    </div>
@endif

<!-- Share Modal -->
<div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shareModalLabel">Share Certificate</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Share this certificate with others:</p>

                <div class="mb-3">
                    <label for="shareUrl" class="form-label">Certificate URL</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="shareUrl" readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('shareUrl')">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="#" id="shareTwitter" class="btn btn-outline-info" target="_blank">
                        <i class="fab fa-twitter me-1"></i>
                        Twitter
                    </a>
                    <a href="#" id="shareLinkedIn" class="btn btn-outline-primary" target="_blank">
                        <i class="fab fa-linkedin me-1"></i>
                        LinkedIn
                    </a>
                    <a href="#" id="shareFacebook" class="btn btn-outline-primary" target="_blank">
                        <i class="fab fa-facebook me-1"></i>
                        Facebook
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function shareCertificate(certificateId, title) {
        const url = `{{ url('/') }}/hall-of-fame/certificates/verify/${certificateId}`;
        const text = `I've earned a security certificate for discovering: ${title}`;

        document.getElementById('shareUrl').value = url;
        document.getElementById('shareTwitter').href =
            `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(url)}`;
        document.getElementById('shareLinkedIn').href =
            `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(url)}`;
        document.getElementById('shareFacebook').href =
            `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;

        new bootstrap.Modal(document.getElementById('shareModal')).show();
    }

    function copyToClipboard(elementId) {
        const element = document.getElementById(elementId);
        element.select();
        element.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(element.value);

        // Show feedback
        const button = element.nextElementSibling;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check"></i>';
        button.classList.remove('btn-outline-secondary');
        button.classList.add('btn-success');

        setTimeout(() => {
            button.innerHTML = originalText;
            button.classList.remove('btn-success');
            button.classList.add('btn-outline-secondary');
        }, 2000);
    }
</script>
