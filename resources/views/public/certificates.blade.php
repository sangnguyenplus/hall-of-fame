<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h1 class="text-center mb-5">Hall of Fame Certificates</h1>

            <div class="alert alert-info mb-4">
                <p>Browse and verify security certificates issued for validated vulnerability reports.</p>
            </div>

            <div class="row mb-5">
                @if ($certificates->count() > 0)
                    @foreach ($certificates as $certificate)
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Certificate {{ $certificate->certificate_id }}</h5>
                                    <p class="card-text">
                                        <strong>Vulnerability:</strong>
                                        {{ $certificate->vulnerabilityReport->title ?? 'N/A' }}<br>
                                        <strong>Researcher:</strong>
                                        {{ $certificate->vulnerabilityReport->researcher_name ?? 'N/A' }}<br>
                                        <strong>Issued:</strong> {{ $certificate->created_at->format('M d, Y') }}<br>
                                        <strong>Severity:</strong>
                                        {{ ucfirst($certificate->vulnerabilityReport->severity ?? 'N/A') }}
                                    </p>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('public.certificates.show', $certificate->certificate_id) }}"
                                            class="btn btn-primary btn-sm">View Details</a>
                                        <a href="{{ route('public.certificates.download', $certificate->certificate_id) }}"
                                            class="btn btn-success btn-sm">Download PDF</a>
                                        <a href="{{ route('public.certificates.verify', $certificate->certificate_id) }}"
                                            class="btn btn-info btn-sm">Verify</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="text-center">
                            <h3>No certificates available</h3>
                            <p>No certificates have been issued yet.</p>
                        </div>
                    </div>
                @endif
            </div>

            @if ($certificates->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $certificates->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
