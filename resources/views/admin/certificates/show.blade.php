@extends(BaseHelper::getAdminMasterLayoutTemplate())

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ trans('plugins/hall-of-fame::certificates.admin.certificate_details') }}</h4>
                    <div class="card-header-actions">
                        <a href="{{ route('certificates.index') }}" class="btn btn-info">
                            <i class="fa fa-arrow-left"></i>
                            {{ trans('plugins/hall-of-fame::certificates.admin.back_to_list') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>{{ trans('plugins/hall-of-fame::certificates.admin.certificate_information') }}</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>{{ trans('plugins/hall-of-fame::certificates.admin.certificate_id') }}:</strong>
                                    </td>
                                    <td>{{ $certificate->certificate_id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>{{ trans('plugins/hall-of-fame::certificates.admin.created_date') }}:</strong>
                                    </td>
                                    <td>{{ $certificate->created_at->format('M d, Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>{{ trans('plugins/hall-of-fame::certificates.admin.status') }}:</strong>
                                    </td>
                                    <td>
                                        @if ($certificate->is_active)
                                            <span
                                                class="badge badge-success">{{ trans('plugins/hall-of-fame::certificates.admin.active') }}</span>
                                        @else
                                            <span
                                                class="badge badge-danger">{{ trans('plugins/hall-of-fame::certificates.admin.inactive') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>{{ trans('plugins/hall-of-fame::certificates.admin.is_signed') }}:</strong>
                                    </td>
                                    <td>
                                        @if ($certificate->is_signed)
                                            <span
                                                class="badge badge-success">{{ trans('plugins/hall-of-fame::certificates.admin.yes') }}</span>
                                        @else
                                            <span
                                                class="badge badge-secondary">{{ trans('plugins/hall-of-fame::certificates.admin.no') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>{{ trans('plugins/hall-of-fame::certificates.admin.vulnerability_report') }}</h5>
                            @if ($certificate->vulnerabilityReport)
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>{{ trans('plugins/hall-of-fame::certificates.admin.title') }}:</strong>
                                        </td>
                                        <td>{{ $certificate->vulnerabilityReport->title }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ trans('plugins/hall-of-fame::certificates.admin.researcher') }}:</strong>
                                        </td>
                                        <td>{{ $certificate->vulnerabilityReport->researcher_name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ trans('plugins/hall-of-fame::certificates.admin.email') }}:</strong>
                                        </td>
                                        <td>{{ $certificate->vulnerabilityReport->researcher_email }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ trans('plugins/hall-of-fame::certificates.admin.severity') }}:</strong>
                                        </td>
                                        <td>
                                            @if ($certificate->vulnerabilityReport->severity)
                                                <span
                                                    class="badge badge-{{ $certificate->vulnerabilityReport->severity === 'critical' ? 'danger' : ($certificate->vulnerabilityReport->severity === 'high' ? 'warning' : 'info') }}">
                                                    {{ ucfirst($certificate->vulnerabilityReport->severity) }}
                                                </span>
                                            @else
                                                {{ trans('plugins/hall-of-fame::certificates.admin.n_a') }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ trans('plugins/hall-of-fame::certificates.admin.status') }}:</strong>
                                        </td>
                                        <td>{{ ucfirst($certificate->vulnerabilityReport->status) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ trans('plugins/hall-of-fame::certificates.admin.published') }}:</strong>
                                        </td>
                                        <td>
                                            @if ($certificate->vulnerabilityReport->is_published)
                                                <span
                                                    class="badge badge-success">{{ trans('plugins/hall-of-fame::certificates.admin.yes') }}</span>
                                            @else
                                                <span
                                                    class="badge badge-secondary">{{ trans('plugins/hall-of-fame::certificates.admin.no') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            @else
                                <p class="text-muted">
                                    {{ trans('plugins/hall-of-fame::certificates.admin.no_vulnerability_report') }}</p>
                            @endif
                        </div>
                    </div>

                    @if ($certificate->vulnerabilityReport && $certificate->vulnerabilityReport->description)
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <h5>{{ trans('plugins/hall-of-fame::certificates.admin.description') }}</h5>
                                <div class="well">
                                    {!! nl2br(e($certificate->vulnerabilityReport->description)) !!}
                                </div>
                            </div>
                        </div>
                    @endif

                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <h5>{{ trans('plugins/hall-of-fame::certificates.admin.actions') }}</h5>
                            <div class="btn-group" role="group">
                                <a href="{{ route('public.certificates.download', $certificate->certificate_id) }}"
                                    class="btn btn-primary" target="_blank">
                                    <i class="fa fa-download"></i>
                                    {{ trans('plugins/hall-of-fame::certificates.admin.download_pdf') }}
                                </a>
                                <a href="{{ route('public.certificates.verify', $certificate->certificate_id) }}"
                                    class="btn btn-info" target="_blank">
                                    <i class="fa fa-shield-alt"></i>
                                    {{ trans('plugins/hall-of-fame::certificates.admin.verify_certificate') }}
                                </a>
                                <a href="{{ route('public.certificates.view', $certificate->certificate_id) }}"
                                    class="btn btn-secondary" target="_blank">
                                    <i class="fa fa-eye"></i>
                                    {{ trans('plugins/hall-of-fame::certificates.admin.view_pdf') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
