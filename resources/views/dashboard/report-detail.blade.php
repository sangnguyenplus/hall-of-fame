@extends('plugins/hall-of-fame::dashboard.layout')

@section('title', 'Report Details')

@include('plugins/hall-of-fame::partials.hof-navigation')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Report Details #{{ $report->id }}</h3>
                    <a href="{{ route('public.hall-of-fame.dashboard.reports') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Reports
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h4>{{ $report->title }}</h4>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <strong>Vulnerability Type:</strong>
                                    <span class="badge badge-info ml-2">{{ $report->vulnerability_type }}</span>
                                </div>
                                <div class="col-md-6">
                                    <strong>Severity:</strong>
                                    <span
                                        class="badge badge-{{ $report->severity == 'critical' ? 'danger' : ($report->severity == 'high' ? 'warning' : ($report->severity == 'medium' ? 'info' : 'secondary')) }} ml-2">
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
                                        class="badge badge-{{ $report->status == 'verified' ? 'success' : ($report->status == 'under_review' ? 'warning' : 'secondary') }} ml-2">
                                        {{ ucwords(str_replace('_', ' ', $report->status)) }}
                                    </span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h5>Description</h5>
                                <div class="border p-3 bg-light rounded">
                                    {!! nl2br(e($report->description)) !!}
                                </div>
                            </div>

                            @if ($report->proof_of_concept)
                                <div class="mb-4">
                                    <h5>Proof of Concept</h5>
                                    <div class="border p-3 bg-light rounded">
                                        <pre><code>{{ $report->proof_of_concept }}</code></pre>
                                    </div>
                                </div>
                            @endif

                            @if ($report->impact)
                                <div class="mb-4">
                                    <h5>Impact</h5>
                                    <div class="border p-3 bg-light rounded">
                                        {!! nl2br(e($report->impact)) !!}
                                    </div>
                                </div>
                            @endif

                            @if ($report->suggested_fix)
                                <div class="mb-4">
                                    <h5>Suggested Fix</h5>
                                    <div class="border p-3 bg-light rounded">
                                        {!! nl2br(e($report->suggested_fix)) !!}
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Report Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <strong>Submitted:</strong><br>
                                        {{ $report->created_at->format('F d, Y \a\t g:i A') }}
                                    </div>

                                    @if ($report->updated_at != $report->created_at)
                                        <div class="mb-3">
                                            <strong>Last Updated:</strong><br>
                                            {{ $report->updated_at->format('F d, Y \a\t g:i A') }}
                                        </div>
                                    @endif

                                    @if ($report->admin_notes)
                                        <div class="mb-3">
                                            <strong>Admin Notes:</strong><br>
                                            <div class="alert alert-info">
                                                {!! nl2br(e($report->admin_notes)) !!}
                                            </div>
                                        </div>
                                    @endif

                                    @if ($report->status == 'verified')
                                        <div class="alert alert-success">
                                            <i class="fas fa-check-circle"></i>
                                            This vulnerability has been verified and is eligible for recognition in the
                                            Hall of Fame.
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if ($report->attachments && $report->attachments->count() > 0)
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5 class="mb-0">Attachments</h5>
                                    </div>
                                    <div class="card-body">
                                        @foreach ($report->attachments as $attachment)
                                            <div class="mb-2">
                                                <a href="{{ $attachment->url }}" target="_blank"
                                                    class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-download"></i> {{ $attachment->filename }}
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
</div>
