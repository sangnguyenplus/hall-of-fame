@include('plugins/hall-of-fame::partials.hof-master')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="hof-card">
                <div class="hof-card-header">
                    <h3 class="hof-card-title mb-0">My Vulnerability Reports</h3>
                    <a href="{{ route('public.vulnerability-reports.create') }}" class="hof-btn hof-btn-primary">
                        <i class="fas fa-plus"></i> Submit New Report
                    </a>
                </div>
                <div class="hof-card-content">
                    @if ($reports->count() > 0)
                        <div class="hof-table-container">
                            <table class="hof-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Severity</th>
                                        <th>Status</th>
                                        <th>Submitted</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reports as $report)
                                        <tr>
                                            <td>#{{ $report->id }}</td>
                                            <td>{{ $report->title }}</td>
                                            <td>
                                                <span class="badge badge-info">{{ $report->vulnerability_type }}</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge badge-{{ $report->severity == 'critical' ? 'danger' : ($report->severity == 'high' ? 'warning' : ($report->severity == 'medium' ? 'info' : 'secondary')) }}">
                                                    {{ ucfirst($report->severity) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge badge-{{ $report->status == 'verified' ? 'success' : ($report->status == 'under_review' ? 'warning' : 'secondary') }}">
                                                    {{ ucwords(str_replace('_', ' ', $report->status)) }}
                                                </span>
                                            </td>
                                            <td>{{ $report->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('public.hall-of-fame.dashboard.reports.detail', $report->id) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $reports->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">No Reports Yet</h4>
                            <p class="text-muted">You haven't submitted any vulnerability reports yet.</p>
                            <a href="{{ route('public.vulnerability-reports.create') }}" class="btn btn-primary">
                                Submit Your First Report
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
