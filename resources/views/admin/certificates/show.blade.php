@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Certificate Details</h4>
                    <div class="card-header-actions">
                        <a href="{{ route('certificates.index') }}" class="btn btn-info">
                            <i class="fa fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Certificate Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Certificate ID:</strong></td>
                                    <td>{{ $certificate->certificate_id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Created Date:</strong></td>
                                    <td>{{ $certificate->created_at->format('M d, Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if ($certificate->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Is Signed:</strong></td>
                                    <td>
                                        @if ($certificate->is_signed)
                                            <span class="badge badge-success">Yes</span>
                                        @else
                                            <span class="badge badge-secondary">No</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Vulnerability Report</h5>
                            @if ($certificate->vulnerabilityReport)
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Title:</strong></td>
                                        <td>{{ $certificate->vulnerabilityReport->title }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Researcher:</strong></td>
                                        <td>{{ $certificate->vulnerabilityReport->researcher_name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email:</strong></td>
                                        <td>{{ $certificate->vulnerabilityReport->researcher_email }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Severity:</strong></td>
                                        <td>
                                            @if ($certificate->vulnerabilityReport->severity)
                                                <span
                                                    class="badge badge-{{ $certificate->vulnerabilityReport->severity === 'critical' ? 'danger' : ($certificate->vulnerabilityReport->severity === 'high' ? 'warning' : 'info') }}">
                                                    {{ ucfirst($certificate->vulnerabilityReport->severity) }}
                                                </span>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>{{ ucfirst($certificate->vulnerabilityReport->status) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Published:</strong></td>
                                        <td>
                                            @if ($certificate->vulnerabilityReport->is_published)
                                                <span class="badge badge-success">Yes</span>
                                            @else
                                                <span class="badge badge-secondary">No</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            @else
                                <p class="text-muted">No vulnerability report associated.</p>
                            @endif
                        </div>
                    </div>

                    @if ($certificate->vulnerabilityReport && $certificate->vulnerabilityReport->description)
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <h5>Description</h5>
                                <div class="well">
                                    {!! nl2br(e($certificate->vulnerabilityReport->description)) !!}
                                </div>
                            </div>
                        </div>
                    @endif

                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <h5>Actions</h5>
                            <div class="btn-group" role="group">
                                <a href="{{ route('public.certificates.download', $certificate->certificate_id) }}"
                                    class="btn btn-primary" target="_blank">
                                    <i class="fa fa-download"></i> Download PDF
                                </a>
                                <a href="{{ route('public.certificates.verify', $certificate->certificate_id) }}"
                                    class="btn btn-info" target="_blank">
                                    <i class="fa fa-shield-alt"></i> Verify Certificate
                                </a>
                                <a href="{{ route('public.certificates.view', $certificate->certificate_id) }}"
                                    class="btn btn-secondary" target="_blank">
                                    <i class="fa fa-eye"></i> View PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
