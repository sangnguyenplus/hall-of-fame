@include('plugins/hall-of-fame::partials.hof-master')

<div class="hof-container">
    <div class="hof-dashboard-header hof-animate-slide-in">
        <div class="hof-flex-between">
            <div>
                <h1 class="hof-heading-primary hof-gradient-text">
                    <i class="fas fa-certificate me-3"></i>
                    {{ trans('plugins/hall-of-fame::dashboard.certificates') }}
                </h1>
                <p class="hof-text-secondary">
                    {{ trans('plugins/hall-of-fame::dashboard.manage_certificates_description') }}</p>
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

    @if ($certificates->count() > 0)
        <div class="hof-grid-cards hof-animate-fade-in">
            @foreach ($certificates as $index => $certificate)
                <div class="hof-card hof-card-modern hof-certificate-card"
                    style="animation-delay: {{ $index * 0.1 }}s">
                    <!-- Certificate Status Badge -->
                    <div class="hof-badge hof-badge-success hof-position-absolute" style="top: 1rem; right: 1rem;">
                        <i class="fas fa-check-circle me-1"></i>
                        Verified
                    </div>

                    <div class="hof-card-content">
                        <!-- Certificate Header -->
                        <div class="hof-flex-center mb-4">
                            <div class="hof-icon-circle hof-icon-xl hof-gradient-primary">
                                <i class="fas fa-certificate fa-2x"></i>
                            </div>
                        </div>

                        <!-- Certificate Details -->
                        <div class="hof-card-body">
                            <h3 class="hof-heading-card hof-mb-3">{{ $certificate->vulnerability_title }}</h3>

                            <div class="hof-info-grid">
                                <div class="hof-info-item">
                                    <span
                                        class="hof-label">{{ trans('plugins/hall-of-fame::dashboard.certificate_id') }}</span>
                                    <span class="hof-value hof-code">{{ $certificate->certificate_id }}</span>
                                </div>

                                <div class="hof-info-item">
                                    <span
                                        class="hof-label">{{ trans('plugins/hall-of-fame::dashboard.vulnerability_type') }}</span>
                                    <span
                                        class="hof-badge hof-badge-primary">{{ $certificate->vulnerability_type }}</span>
                                </div>

                                <div class="hof-info-item">
                                    <span
                                        class="hof-label">{{ trans('plugins/hall-of-fame::dashboard.issued_date') }}</span>
                                    <span class="hof-value">
                                        {{ $certificate->acknowledgment_date ? \Carbon\Carbon::parse($certificate->acknowledgment_date)->format('M d, Y') : trans('plugins/hall-of-fame::dashboard.not_available') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="hof-card-actions hof-mt-4">
                                <a href="{{ route('public.certificates.view', $certificate->certificate_id) }}"
                                    class="hof-btn hof-btn-primary hof-btn-sm hof-animate-hover" target="_blank">
                                    <i class="fas fa-eye me-1"></i>
                                    {{ trans('plugins/hall-of-fame::dashboard.view') }}
                                </a>

                                <a href="{{ route('public.certificates.download', $certificate->certificate_id) }}"
                                    class="hof-btn hof-btn-success hof-btn-sm hof-animate-hover">
                                    <i class="fas fa-download me-1"></i>
                                    {{ trans('plugins/hall-of-fame::dashboard.download') }}
                                </a>

                                <a href="{{ route('public.certificates.verify', $certificate->certificate_id) }}"
                                    class="hof-btn hof-btn-secondary hof-btn-sm hof-animate-hover" target="_blank">
                                    <i class="fas fa-shield-alt me-1"></i>
                                    {{ trans('plugins/hall-of-fame::dashboard.verify') }}
                                </a>

                                <button class="hof-btn hof-btn-outline hof-btn-sm hof-animate-hover"
                                    onclick="shareCertificate('{{ $certificate->certificate_id }}', '{{ $certificate->vulnerability_title }}')">
                                    <i class="fas fa-share me-1"></i>
                                    {{ trans('plugins/hall-of-fame::dashboard.share') }}
                                </button>
                            </div>
                        </div>

                        @if ($certificate->description)
                            <div class="hof-mt-4 hof-pt-4 hof-border-top">
                                <h6 class="hof-label hof-mb-2">
                                    {{ trans('plugins/hall-of-fame::dashboard.description') }}</h6>
                                <p class="hof-text-secondary">{{ $certificate->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if ($certificates->hasPages())
            <div class="hof-pagination-wrapper hof-mt-6">
                {{ $certificates->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="hof-card hof-card-modern hof-text-center hof-py-8">
            <div class="hof-empty-state">
                <div class="hof-icon-circle hof-icon-xl hof-gradient-muted hof-mb-4">
                    <i class="fas fa-certificate fa-3x"></i>
                </div>
                <h3 class="hof-heading-card hof-mb-2">
                    {{ trans('plugins/hall-of-fame::dashboard.no_certificates_title') }}</h3>
                <p class="hof-text-secondary hof-mb-4">
                    {{ trans('plugins/hall-of-fame::dashboard.no_certificates_description') }}</p>
                <a href="{{ route('public.vulnerability-reports.create') }}"
                    class="hof-btn hof-btn-primary hof-animate-hover">
                    <i class="fas fa-bug me-2"></i>
                    {{ trans('plugins/hall-of-fame::dashboard.submit_vulnerability') }}
                </a>
            </div>
        </div>
    @endif
</div>
<i class="fas fa-certificate fa-4x"></i>
</div>
<h4 class="hof-empty-title">{{ trans('plugins/hall-of-fame::dashboard.no_certificates') }}</h4>
<p class="hof-empty-text">{{ trans('plugins/hall-of-fame::dashboard.no_certificates_description') }}</p>
<a href="{{ route('public.vulnerability-reports.create') }}" class="hof-btn hof-btn-primary">
    <i class="fas fa-plus me-2"></i>
    {{ trans('plugins/hall-of-fame::dashboard.submit_new_report') }}
</a>
</div>
</div>
@endif

<!-- Share Modal -->
<div class="hof-modal" id="shareModal" style="display: none;">
    <div class="hof-modal-dialog">
        @endif
    </div>

    <!-- Share Modal -->
    <div id="shareModal" class="hof-modal" style="display: none;">
        <div class="hof-modal-backdrop" onclick="closeShareModal()"></div>
        <div class="hof-modal-dialog">
            <div class="hof-modal-content">
                <div class="hof-modal-header">
                    <h5 class="hof-modal-title">
                        <i class="fas fa-share me-2"></i>
                        {{ trans('plugins/hall-of-fame::dashboard.share_certificate') }}
                    </h5>
                    <button type="button" class="hof-modal-close" onclick="closeShareModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="hof-modal-body">
                    <p class="hof-text-secondary hof-mb-4">
                        {{ trans('plugins/hall-of-fame::dashboard.share_certificate_description') }}</p>

                    <div class="hof-form-group">
                        <label for="shareUrl"
                            class="hof-form-label">{{ trans('plugins/hall-of-fame::dashboard.certificate_url') }}</label>
                        <div class="hof-input-group">
                            <input type="text" class="hof-form-control" id="shareUrl" readonly>
                            <button class="hof-btn hof-btn-secondary" type="button"
                                onclick="copyToClipboard('shareUrl')">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>

                    <div class="hof-share-buttons hof-mt-4">
                        <a href="#" id="shareTwitter" class="hof-btn hof-btn-primary" target="_blank">
                            <i class="fab fa-twitter me-1"></i>
                            {{ trans('plugins/hall-of-fame::dashboard.share_twitter') }}
                        </a>
                        <a href="#" id="shareLinkedIn" class="hof-btn hof-btn-primary" target="_blank">
                            <i class="fab fa-linkedin me-1"></i>
                            {{ trans('plugins/hall-of-fame::dashboard.share_linkedin') }}
                        </a>
                        <a href="#" id="shareFacebook" class="hof-btn hof-btn-primary" target="_blank">
                            <i class="fab fa-facebook me-1"></i>
                            {{ trans('plugins/hall-of-fame::dashboard.share_facebook') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        < /div> <
        /div>

        <
        script >
            function shareCertificate(certificateId, title) {
                const url = `{{ url('/') }}/hall-of-fame/certificates/verify/${certificateId}`;
                const text = `{{ trans('plugins/hall-of-fame::dashboard.share_text_prefix') }}: ${title}`;

                document.getElementById('shareUrl').value = url;
                document.getElementById('shareTwitter').href =
                    `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(url)}`;
                document.getElementById('shareLinkedIn').href =
                    `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(url)}`;
                document.getElementById('shareFacebook').href =
                    `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;

                document.getElementById('shareModal').style.display = 'block';
            }

        function closeShareModal() {
            document.getElementById('shareModal').style.display = 'none';
        }

        function copyToClipboard(elementId) {
            const element = document.getElementById(elementId);
            element.select();
            element.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(element.value);

            // Show modern notification
            if (window.showNotification) {
                window.showNotification('Certificate URL copied to clipboard!', 'success');
            }

            // Update button temporarily
            const button = element.nextElementSibling;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check"></i>';
            button.classList.remove('hof-btn-secondary');
            button.classList.add('hof-btn-success');

            setTimeout(() => {
                button.innerHTML = originalText;
                button.classList.remove('hof-btn-success');
                button.classList.add('hof-btn-secondary');
            }, 2000);
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('shareModal');
            if (event.target == modal) {
                closeShareModal();
            }
        }
    </script>
