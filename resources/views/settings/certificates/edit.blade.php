@extends('core/base::layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> Please fix the following issues:
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="fa fa-certificate"></i> Certificate Settings
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('hall-of-fame.settings.certificates.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Template Settings -->
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6><i class="fa fa-palette"></i> Template Settings</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="certificate_template_style">Template Style</label>
                                                <select class="form-control" id="certificate_template_style"
                                                    name="certificate_template_style">
                                                    <option value="professional"
                                                        {{ old('certificate_template_style', $settings['certificate_template_style']) == 'professional' ? 'selected' : '' }}>
                                                        Professional
                                                    </option>
                                                    <option value="modern"
                                                        {{ old('certificate_template_style', $settings['certificate_template_style']) == 'modern' ? 'selected' : '' }}>
                                                        Modern
                                                    </option>
                                                    <option value="classic"
                                                        {{ old('certificate_template_style', $settings['certificate_template_style']) == 'classic' ? 'selected' : '' }}>
                                                        Classic
                                                    </option>
                                                </select>
                                                <small class="form-text text-muted">Choose the visual style for generated
                                                    certificates</small>
                                            </div>

                                            <div class="form-group">
                                                <label for="certificate_language_default">Default Language</label>
                                                <select class="form-control" id="certificate_language_default"
                                                    name="certificate_language_default">
                                                    <option value="en"
                                                        {{ old('certificate_language_default', $settings['certificate_language_default']) == 'en' ? 'selected' : '' }}>
                                                        English
                                                    </option>
                                                    <option value="ar"
                                                        {{ old('certificate_language_default', $settings['certificate_language_default']) == 'ar' ? 'selected' : '' }}>
                                                        Arabic
                                                    </option>
                                                </select>
                                                <small class="form-text text-muted">Default language for new
                                                    certificates</small>
                                            </div>

                                            <div class="form-group">
                                                <label for="certificate_watermark_opacity">Watermark Opacity</label>
                                                <input type="range" class="form-control-range"
                                                    id="certificate_watermark_opacity" name="certificate_watermark_opacity"
                                                    min="0" max="1" step="0.1"
                                                    value="{{ old('certificate_watermark_opacity', $settings['certificate_watermark_opacity']) }}">
                                                <div class="d-flex justify-content-between">
                                                    <small>Transparent</small>
                                                    <small>Current: <span
                                                            id="opacity-value">{{ $settings['certificate_watermark_opacity'] }}</span></small>
                                                    <small>Opaque</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Generation Settings -->
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6><i class="fa fa-cogs"></i> Generation Settings</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="certificate_auto_generate" name="certificate_auto_generate"
                                                        value="1"
                                                        {{ old('certificate_auto_generate', $settings['certificate_auto_generate']) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="certificate_auto_generate">
                                                        Auto-generate certificates
                                                    </label>
                                                    <small class="form-text text-muted">Automatically generate certificates
                                                        when reports are approved</small>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="certificate_include_qr_code" name="certificate_include_qr_code"
                                                        value="1"
                                                        {{ old('certificate_include_qr_code', $settings['certificate_include_qr_code']) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="certificate_include_qr_code">
                                                        Include QR Code
                                                    </label>
                                                    <small class="form-text text-muted">Add QR code for verification</small>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="certificate_signature_required"
                                                        name="certificate_signature_required" value="1"
                                                        {{ old('certificate_signature_required', $settings['certificate_signature_required']) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="certificate_signature_required">
                                                        PGP Signature Required
                                                    </label>
                                                    <small class="form-text text-muted">Require PGP signatures for all
                                                        certificates</small>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="certificate_batch_processing"
                                                        name="certificate_batch_processing" value="1"
                                                        {{ old('certificate_batch_processing', $settings['certificate_batch_processing']) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="certificate_batch_processing">
                                                        Enable Batch Processing
                                                    </label>
                                                    <small class="form-text text-muted">Allow bulk certificate
                                                        generation</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Export Settings -->
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6><i class="fa fa-download"></i> Export Settings</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label>Export Formats</label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="format_pdf"
                                                        name="certificate_export_formats[]" value="pdf"
                                                        {{ in_array('pdf', $settings['certificate_export_formats']) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="format_pdf">PDF</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="format_png"
                                                        name="certificate_export_formats[]" value="png"
                                                        {{ in_array('png', $settings['certificate_export_formats']) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="format_png">PNG Image</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="format_jpg"
                                                        name="certificate_export_formats[]" value="jpg"
                                                        {{ in_array('jpg', $settings['certificate_export_formats']) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="format_jpg">JPG Image</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delivery Settings -->
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6><i class="fa fa-paper-plane"></i> Delivery & Verification</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="certificate_email_delivery" name="certificate_email_delivery"
                                                        value="1"
                                                        {{ old('certificate_email_delivery', $settings['certificate_email_delivery']) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="certificate_email_delivery">
                                                        Email Delivery
                                                    </label>
                                                    <small class="form-text text-muted">Automatically email certificates to
                                                        researchers</small>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="certificate_public_verification"
                                                        name="certificate_public_verification" value="1"
                                                        {{ old('certificate_public_verification', $settings['certificate_public_verification']) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="certificate_public_verification">
                                                        Public Verification
                                                    </label>
                                                    <small class="form-text text-muted">Allow public verification of
                                                        certificates</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-save"></i> Save Certificate Settings
                                        </button>
                                        <button type="button" class="btn btn-secondary" onclick="location.reload()">
                                            <i class="fa fa-undo"></i> Reset
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer')
    <script>
        // Update opacity value display
        document.getElementById('certificate_watermark_opacity').addEventListener('input', function() {
            document.getElementById('opacity-value').textContent = this.value;
        });
    </script>
@endpush
