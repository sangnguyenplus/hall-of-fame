@include('plugins/hall-of-fame::partials.hof-master')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="hof-card">
                <div class="hof-card-header">
                    <h2 class="hof-card-title">Certificate Details</h2>
                </div>
                <div class="hof-card-content">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Certificate Information</h4>
                            <table class="hof-table hof-table-borderless">
                                <tr>
                                    <td><strong>Certificate ID:</strong></td>
                                    <td>{{ $certificate->certificate_id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Issued Date:</strong></td>
                                    <td>{{ $certificate->created_at->format('M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if ($certificate->is_active)
                                            <span class="hof-badge hof-badge-success">Valid</span>
                                        @else
                                            <span class="hof-badge hof-badge-danger">Revoked</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h4>Vulnerability Details</h4>
                            <table class="hof-table hof-table-borderless">
                                <tr>
                                    <td><strong>Title:</strong></td>
                                    <td>{{ $certificate->vulnerabilityReport->title ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Researcher:</strong></td>
                                    <td>{{ $certificate->vulnerabilityReport->researcher_name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Severity:</strong></td>
                                    <td>
                                        @if ($certificate->vulnerabilityReport->severity)
                                            <span
                                                class="hof-badge hof-badge-{{ $certificate->vulnerabilityReport->severity === 'critical' ? 'danger' : ($certificate->vulnerabilityReport->severity === 'high' ? 'warning' : 'info') }}">
                                                {{ ucfirst($certificate->vulnerabilityReport->severity) }}
                                            </span>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Discovery Date:</strong></td>
                                    <td>{{ $certificate->vulnerabilityReport->created_at->format('M d, Y') ?? 'N/A' }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-12">
                            <h4>Description</h4>
                            <p>{{ $certificate->vulnerabilityReport->description ?? 'No description available.' }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-12">
                            <div class="hof-action-buttons">
                                <a href="{{ route('public.certificates.download', $certificate->certificate_id) }}"
                                    class="hof-btn hof-btn-primary">
                                    <i class="fas fa-download"></i> Download PDF
                                </a>
                                <a href="{{ route('public.certificates.verify', $certificate->certificate_id) }}"
                                    class="hof-btn hof-btn-secondary">
                                    <i class="fas fa-shield-alt"></i> Verify Certificate
                                </a>
                                <a href="{{ route('public.certificates.index') }}" class="hof-btn hof-btn-info">
                                    <i class="fas fa-list"></i> All Certificates
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
