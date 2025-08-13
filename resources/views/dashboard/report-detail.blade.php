@include('plugins/hall-of-fame::partials.hof-master')

<div class="container-fluid">
    <div class="dashboard-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="hof-title">Report Details #{{ $report->id }}</h1>
                <p class="text-muted mb-0">{{ $report->title }}</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('public.hall-of-fame.dashboard.reports') }}" class="hof-btn hof-btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Reports
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="hof-card mb-4">
                <div class="hof-card-header">
                    <div class="d-flex align-items-center">
                        <div class="hof-icon-circle hof-bg-primary me-3">
                            <i class="fas fa-bug text-white"></i>
                        </div>
                        <div>
                            <h5 class="hof-card-title mb-0">{{ $report->title }}</h5>
                            <small class="text-muted">Vulnerability Report Details</small>
                        </div>
                    </div>
                </div>
                <div class="hof-card-content">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <strong>Vulnerability Type:</strong>
                            <span class="hof-badge hof-badge-info ms-2">{{ $report->vulnerability_type }}</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Severity:</strong>
                            <span
                                class="hof-badge hof-badge-{{ $report->severity == 'critical' ? 'danger' : ($report->severity == 'high' ? 'warning' : ($report->severity == 'medium' ? 'info' : 'secondary')) }} ms-2">
                                {{ ucfirst($report->severity) }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <strong>URL/Endpoint:</strong>
                            <p class="mt-1"><code>{{ $report->url }}</code></p>
                        </div>
                        <div class="col-md-6">
                            <strong>Status:</strong>
                            <span
                                class="hof-badge hof-badge-{{ $report->status == 'verified' ? 'success' : ($report->status == 'under_review' ? 'warning' : 'secondary') }} ms-2">
                                {{ ucwords(str_replace('_', ' ', $report->status)) }}
                            </span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 class="hof-section-title">Description</h5>
                        <div class="hof-content-box">
                            {!! nl2br(e($report->description)) !!}
                        </div>
                    </div>

                    @if ($report->proof_of_concept)
                        <div class="mb-4">
                            <h5 class="hof-section-title">Proof of Concept</h5>
                            <div class="hof-code-box">
                                <pre><code>{{ $report->proof_of_concept }}</code></pre>
                            </div>
                        </div>
                    @endif

                    @if ($report->impact)
                        <div class="mb-4">
                            <h5 class="hof-section-title">Impact</h5>
                            <div class="hof-content-box">
                                {!! nl2br(e($report->impact)) !!}
                            </div>
                        </div>
                    @endif

                    @if ($report->suggested_fix)
                        <div class="mb-4">
                            <h5 class="hof-section-title">Suggested Fix</h5>
                            <div class="hof-content-box">
                                {!! nl2br(e($report->suggested_fix)) !!}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="hof-card">
                <div class="hof-card-header">
                    <div class="d-flex align-items-center">
                        <div class="hof-icon-circle hof-bg-info me-3">
                            <i class="fas fa-info-circle text-white"></i>
                        </div>
                        <h5 class="hof-card-title mb-0">Report Information</h5>
                    </div>
                </div>
                <div class="hof-card-content">
                    <div class="mb-3">
                        <strong>Submitted:</strong><br>
                        <span class="text-muted">{{ $report->created_at->format('F d, Y \a\t g:i A') }}</span>
                    </div>

                    @if ($report->updated_at != $report->created_at)
                        <div class="mb-3">
                            <strong>Last Updated:</strong><br>
                            <span class="text-muted">{{ $report->updated_at->format('F d, Y \a\t g:i A') }}</span>
                        </div>
                    @endif

                    @if ($report->admin_notes)
                        <div class="mb-3">
                            <strong>Admin Notes:</strong><br>
                            <div class="hof-alert hof-alert-info">
                                {!! nl2br(e($report->admin_notes)) !!}
                            </div>
                        </div>
                    @endif

                    @if ($report->status == 'verified')
                        <div class="hof-alert hof-alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            This vulnerability has been verified and is eligible for recognition in the Hall of Fame.
                        </div>
                    @endif
                </div>
            </div>

            @if ($report->attachments && $report->attachments->count() > 0)
                <div class="hof-card mt-4">
                    <div class="hof-card-header">
                        <div class="d-flex align-items-center">
                            <div class="hof-icon-circle hof-bg-secondary me-3">
                                <i class="fas fa-paperclip text-white"></i>
                            </div>
                            <h5 class="hof-card-title mb-0">Attachments</h5>
                        </div>
                    </div>
                    <div class="hof-card-content">
                        @foreach ($report->attachments as $attachment)
                            <div class="mb-2">
                                <a href="{{ $attachment->url }}" target="_blank"
                                    class="hof-btn hof-btn-outline hof-btn-sm">
                                    <i class="fas fa-download me-2"></i>{{ $attachment->filename }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
</div>
</div>
</div>
