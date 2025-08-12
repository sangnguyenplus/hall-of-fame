<!DOCTYPE html>
<html dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <title>{{ trans('plugins/hall-of-fame::certificates.certificate.page_title') }}</title>
    <style>
        @page {
            /* Standard A4 margins */
            margin: 15mm;
            size: A4 landscape;
        }

        body {
            font-family: {{ app()->getLocale() == 'ar' ? '"Tahoma", "Arial Unicode MS"' : '"Segoe UI", "Roboto", Arial' }}, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #333;
            font-size: 6px;
            width: 100%;
            height: 100%;
            direction: {{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }};
        }

        .certificate {
            width: 100%;
            border-radius: 15px;
            position: relative;
            background: white;
            padding: 15px;
            box-sizing: border-box;
            page-break-inside: avoid;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .inner-border {
            border: 3px solid transparent;
            background: linear-gradient(45deg, #667eea, #764ba2) border-box;
            border-radius: 10px;
            padding: 8mm;
            box-sizing: border-box;
            position: relative;
        }

        /* Modern geometric patterns */
        .certificate::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 50%, #667eea 100%);
            border-radius: 15px 15px 0 0;
        }

        /* Watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 200px;
            height: auto;
            opacity: {{ $settings['watermark_opacity'] ?? 0.1 }};
            z-index: 1;
        }

        /* Modern typography */
        .title {
            font-size: 32px;
            font-weight: 300;
            color: #667eea;
            margin: 0;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .subtitle {
            font-size: 14px;
            color: #764ba2;
            font-weight: 400;
            letter-spacing: 1px;
            margin: 2mm 0 4mm 0;
            text-transform: uppercase;
        }

        .company-name {
            font-size: 18px;
            color: #667eea;
            font-weight: 600;
            margin-bottom: 2mm;
            letter-spacing: 0.5px;
        }

        /* Modern card-style layout */
        .header {
            text-align: center;
            padding: 0 0 6mm 0;
            margin: 0 0 4mm 0;
            position: relative;
        }

        .header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: 2px;
        }

        /* Certificate content with modern spacing */
        .cert-text {
            font-size: 14px;
            line-height: 1.6;
            color: #555;
            margin: 4mm 0;
            text-align: center;
            font-weight: 300;
        }

        .researcher-name {
            font-size: 24px;
            font-weight: 600;
            color: #764ba2;
            margin: 3mm 0;
            text-align: center;
            letter-spacing: 0.5px;
        }

        /* Modern details layout */
        .details-section {
            margin-top: 6mm;
            padding: 4mm;
            background: rgba(102, 126, 234, 0.05);
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }

        .detail {
            font-size: 11px;
            margin: 2mm 0;
            color: #666;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .detail strong {
            color: #667eea;
            font-weight: 600;
        }

        /* Modern footer */
        .footer {
            margin-top: 8mm;
            padding-top: 4mm;
            border-top: 1px solid #e0e6ed;
            text-align: center;
        }

        .verification-note {
            background: rgba(118, 75, 162, 0.08);
            border: 1px solid rgba(118, 75, 162, 0.2);
            border-radius: 8px;
            padding: 3mm;
            font-size: 9px;
            color: #764ba2;
            text-align: center;
        }

        /* Responsive adjustments for Arabic */
        .{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }} .detail {
            {{ app()->getLocale() == 'ar' ? 'flex-direction: row-reverse;' : '' }}
        }

        /* Modern accent elements */
        .accent-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            background: linear-gradient(45deg, #667eea, #764ba2);
            border-radius: 50%;
            margin: 0 5px;
        }
    </style>
</head>

<body>
    <div class="certificate">
        <div class="inner-border">
            <img class="watermark" src="{{ public_path('vendor/core/plugins/hall-of-fame/images/logo.png') }}"
                alt="">

            <!-- Modern Header -->
            <div class="header">
                <div class="company-name">{{ trans('plugins/hall-of-fame::certificates.certificate.company_name') }}
                </div>
                <div class="title">{{ trans('plugins/hall-of-fame::certificates.certificate.title') }}</div>
                <div class="subtitle">
                    <span class="accent-dot"></span>
                    {{ trans('plugins/hall-of-fame::certificates.certificate.subtitle') }}
                    <span class="accent-dot"></span>
                </div>
            </div>

            <!-- Certificate Content -->
            <div class="cert-text">{{ trans('plugins/hall-of-fame::certificates.certificate.awarded_to') }}</div>

            <div class="researcher-name">{{ $certificate->researcher_name }}</div>

            <div class="cert-text">
                {{ trans('plugins/hall-of-fame::certificates.certificate.for_contribution') }}
                <strong style="color: #667eea;">{{ $certificate->vulnerability_title }}</strong>
            </div>

            <!-- Modern Details Section -->
            <div class="details-section">
                <div class="detail">
                    <strong>{{ trans('plugins/hall-of-fame::certificates.certificate.details.vulnerability') }}:</strong>
                    <span>{{ $certificate->vulnerability_title }}</span>
                </div>
                <div class="detail">
                    <strong>{{ trans('plugins/hall-of-fame::certificates.certificate.details.type') }}:</strong>
                    <span>{{ $certificate->vulnerability_type }}</span>
                </div>
                <div class="detail">
                    <strong>{{ trans('plugins/hall-of-fame::certificates.certificate.details.discovery') }}:</strong>
                    <span>{{ $certificate->discovery_date->format('M j, Y') }}</span>
                </div>
                <div class="detail">
                    <strong>{{ trans('plugins/hall-of-fame::certificates.certificate.details.acknowledged') }}:</strong>
                    <span>{{ $certificate->acknowledgment_date->format('M j, Y') }}</span>
                </div>
                <div class="detail">
                    <strong>{{ trans('plugins/hall-of-fame::certificates.certificate.details.certificate_id') }}:</strong>
                    <span>{{ $certificate->certificate_id }}</span>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <!-- Dynamic PGP Signature Section -->
                @if (($settings['signature_required'] ?? false) && !empty($pgpSignature))
                    <div
                        style="background: rgba(102, 126, 234, 0.1); border-radius: 8px; padding: 4mm; margin-bottom: 3mm;">
                        <div style="font-weight: 600; color: #667eea; margin-bottom: 2mm;">
                            🔐
                            {{ trans('plugins/hall-of-fame::certificates.certificate.signature.digital_signature') }}
                        </div>
                        <div
                            style="font-family: monospace; font-size: 6px; color: #666; max-height: 40px; overflow: hidden;">
                            {{ $pgpSignature }}</div>
                    </div>
                @endif

                <div class="verification-note">
                    {{ trans('plugins/hall-of-fame::certificates.certificate.security.verification_required') }}
                </div>

                <!-- Dynamic QR Code Section -->
                @if (($settings['include_qr_code'] ?? false) && !empty($qrCodeUrl))
                    <div style="position: absolute; bottom: 15mm; right: 15mm; text-align: center;">
                        <div
                            style="width: 50px; height: 50px; border: 2px solid #667eea; margin: 0 auto 5px; display: flex; align-items: center; justify-content: center; font-size: 8px; background: linear-gradient(45deg, #667eea, #764ba2); color: white; border-radius: 5px;">
                            {{ trans('plugins/hall-of-fame::certificates.certificate.qr_code') }}</div>
                        <div style="font-size: 6px; color: #667eea;">
                            {{ trans('plugins/hall-of-fame::certificates.certificate.details.verify') }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>

</html>
