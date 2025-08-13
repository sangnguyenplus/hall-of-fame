@include('plugins/hall-of-fame::partials.hof-master')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h1 class="hof-title text-center mb-5">
                {{ trans('plugins/hall-of-fame::certificates.public.hall_of_fame_certificates') }}</h1>

            <div class="hof-info-banner mb-4">
                <p>{{ trans('plugins/hall-of-fame::certificates.public.browse_certificates_info') }}</p>
            </div>

            <div class="row mb-5">
                @if ($certificates->count() > 0)
                    @foreach ($certificates as $certificate)
                        <div class="col-md-6 mb-4">
                            <div class="hof-card hof-report-card h-100">
                                <div class="hof-card-content">
                                    <h4 class="hof-card-title">
                                        {{ trans('plugins/hall-of-fame::certificates.public.certificate') }}
                                        {{ $certificate->certificate_id }}
                                    </h4>
                                    <h6 class="hof-card-subtitle">
                                        {{ $certificate->vulnerabilityReport->vulnerability_type ?? 'Security Research' }}
                                        - {{ $certificate->created_at->format('M d, Y') }}
                                    </h6>
                                    <p class="hof-card-text">
                                        <strong>{{ trans('plugins/hall-of-fame::certificates.public.researcher') }}:</strong>
                                        {{ $certificate->vulnerabilityReport->researcher_name ?? trans('plugins/hall-of-fame::certificates.public.not_available') }}
                                    </p>
                                    <p class="hof-card-text">
                                        <strong>{{ trans('plugins/hall-of-fame::certificates.public.vulnerability') }}:</strong>
                                        {{ $certificate->vulnerabilityReport->title ?? trans('plugins/hall-of-fame::certificates.public.not_available') }}
                                    </p>
                                    @if ($certificate->vulnerabilityReport->severity)
                                        <p class="hof-card-text">
                                            <strong>{{ trans('plugins/hall-of-fame::certificates.public.severity') }}:</strong>
                                            <span
                                                class="hof-badge hof-badge-{{ $certificate->vulnerabilityReport->severity === 'Critical' ? 'danger' : ($certificate->vulnerabilityReport->severity === 'High' ? 'warning' : 'success') }}">
                                                {{ ucfirst($certificate->vulnerabilityReport->severity) }}
                                            </span>
                                        </p>
                                    @endif
                                </div>
                                <div class="hof-card-footer">
                                    <a href="{{ route('public.certificates.show', $certificate->certificate_id) }}"
                                        class="hof-btn hof-btn-sm hof-btn-outline me-2">
                                        {{ trans('plugins/hall-of-fame::certificates.public.view_details') }}
                                    </a>
                                    <a href="{{ route('public.certificates.download', $certificate->certificate_id) }}"
                                        class="hof-btn hof-btn-sm hof-btn-primary me-2">
                                        {{ trans('plugins/hall-of-fame::certificates.public.download_pdf') }}
                                    </a>
                                    <a href="{{ route('public.certificates.verify', $certificate->certificate_id) }}"
                                        class="hof-btn hof-btn-sm hof-btn-info">
                                        {{ trans('plugins/hall-of-fame::certificates.public.verify') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="hof-empty-state">
                            <i class="fas fa-certificate"></i>
                            <p>{{ trans('plugins/hall-of-fame::certificates.public.no_certificates_available') }}</p>
                        </div>
                    </div>
                @endif
            </div>

            @if ($certificates->hasPages())
                <div class="hof-pagination-wrapper">
                    {!! $certificates->links() !!}
                </div>
            @endif
        </div>
    </div>
</div>
