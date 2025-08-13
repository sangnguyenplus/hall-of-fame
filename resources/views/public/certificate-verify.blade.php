@include('plugins/hall-of-fame::partials.hof-master')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="hof-flex-between mb-4">
                <h1 class="hof-title">Verify Certificate</h1>
                <a href="{{ route('public.hall-of-fame.index') }}" class="hof-btn hof-btn-sm hof-btn-secondary">Back to
                    Hall
                    of Fame</a>
            </div>

            @if (!empty($verification['valid']))
                <div class="hof-success-banner">
                    <strong>Valid certificate.</strong> Details are shown below.
                </div>

                <div class="hof-card mb-4">
                    <div class="hof-card-content">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-2"><strong>Certificate ID:</strong>
                                    {{ $verification['certificate_id'] ?? $certificateId }}</div>
                                <div class="mb-2"><strong>Researcher:</strong>
                                    {{ $verification['researcher_name'] ?? 'N/A' }}</div>
                                <div class="mb-2"><strong>Vulnerability:</strong>
                                    {{ $verification['vulnerability_title'] ?? 'N/A' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2"><strong>Discovery Date:</strong>
                                    {{ $verification['discovery_date'] ?? 'N/A' }}</div>
                                <div class="mb-2"><strong>Acknowledged:</strong>
                                    {{ $verification['acknowledgment_date'] ?? 'N/A' }}</div>
                                <div class="mb-2">
                                    <strong>Signed:</strong>
                                    @if (!empty($verification['is_signed']))
                                        <span class="hof-badge hof-badge-success">Yes</span>
                                        @if (array_key_exists('signature_valid', $verification))
                                            <span
                                                class="ms-2 hof-badge {{ $verification['signature_valid'] ? 'hof-badge-success' : 'hof-badge-danger' }}">
                                                {{ $verification['signature_valid'] ? 'Signature Verified' : 'Signature Invalid' }}
                                            </span>
                                        @endif
                                    @else
                                        <span class="hof-badge hof-badge-secondary">No</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="hof-action-buttons">
                            <a class="hof-btn hof-btn-primary"
                                href="{{ route('public.certificates.view', $certificateId) }}" target="_blank">View
                                PDF</a>
                            <a class="hof-btn hof-btn-secondary"
                                href="{{ route('public.certificates.download', $certificateId) }}">Download PDF</a>
                            <a class="hof-btn hof-btn-info"
                                href="{{ route('public.certificates.verify-api', $certificateId) }}"
                                target="_blank">View JSON</a>
                        </div>
                    </div>
                </div>

                <div class="hof-info-text">
                    Tip: Share the verification link below to allow others to confirm the authenticity of this
                    certificate.
                    <div class="mt-2"><code>{{ route('public.certificates.verify', $certificateId) }}</code></div>
                </div>
            @else
                <div class="hof-error-banner">
                    <strong>Invalid certificate.</strong>
                    {{ $verification['message'] ?? 'The requested certificate could not be verified.' }}
                </div>

                <a class="hof-btn hof-btn-primary" href="{{ route('public.hall-of-fame.index') }}">Go to Hall of
                    Fame</a>
            @endif
        </div>
    </div>
</div>
