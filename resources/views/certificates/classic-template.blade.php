<!DOCTYPE html>
<html dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <title>{{ trans('plugins/hall-of-fame::certificates.certificate.page_title') }}</title>
    <style>
        @page {
            /* Standard A4 margins */
            margin: 25mm;
            size: A4 landscape;
        }

        body {
            font-family: {{ app()->getLocale() == 'ar' ? '"Tahoma", "Arial Unicode MS"' : '"Times New Roman", "Georgia", serif' }}, serif;
            margin: 0;
            padding: 0;
            background: #f8f6f0;
            color: #2c1810;
            font-size: 6px;
            width: 100%;
            height: 100%;
            direction: {{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }};
        }

        .certificate {
            width: 100%;
            border: 5px solid #8b4513;
            position: relative;
            background: #fffef7;
            padding: 20px;
            box-sizing: border-box;
            page-break-inside: avoid;
            box-shadow: inset 0 0 50px rgba(139, 69, 19, 0.1);
        }

        .inner-border {
            border: 2px solid #d4af37;
            padding: 15mm;
            box-sizing: border-box;
            position: relative;
            background: radial-gradient(circle at center, rgba(212, 175, 55, 0.05) 0%, transparent 70%);
        }

        /* Classic ornamental corners */
        .corner-ornament {
            position: absolute;
            width: 40px;
            height: 40px;
            border: 2px solid #d4af37;
        }

        .corner-ornament::before,
        .corner-ornament::after {
            content: '';
            position: absolute;
            background: #d4af37;
        }

        .corner-ornament.top-left {
            top: -2px;
            left: -2px;
            border-right: none;
            border-bottom: none;
        }

        .corner-ornament.top-right {
            top: -2px;
            right: -2px;
            border-left: none;
            border-bottom: none;
        }

        .corner-ornament.bottom-left {
            bottom: -2px;
            left: -2px;
            border-right: none;
            border-top: none;
        }

        .corner-ornament.bottom-right {
            bottom: -2px;
            right: -2px;
            border-left: none;
            border-top: none;
        }

        /* Watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 180px;
            height: auto;
            opacity: {{ $settings['watermark_opacity'] ?? 0.1 }};
            z-index: 1;
        }

        /* Classic typography */
        .title {
            font-size: 36px;
            font-weight: bold;
            color: #8b4513;
            margin: 0;
            letter-spacing: 3px;
            font-family: {{ app()->getLocale() == 'ar' ? '"Tahoma", "Arial Unicode MS"' : '"Old English Text MT", "Times New Roman"' }}, serif;
            text-shadow: 1px 1px 2px rgba(139, 69, 19, 0.2);
        }

        .subtitle {
            font-size: 16px;
            color: #d4af37;
            font-weight: normal;
            font-style: italic;
            letter-spacing: 2px;
            margin: 3mm 0 5mm 0;
        }

        .company-name {
            font-size: 20px;
            color: #8b4513;
            font-weight: bold;
            margin-bottom: 3mm;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* Classic formal layout */
        .header {
            text-align: center;
            border-bottom: 3px double #d4af37;
            padding: 0 0 8mm 0;
            margin: 0 0 6mm 0;
            position: relative;
        }

        .header::before {
            content: '❦';
            position: absolute;
            bottom: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: #fffef7;
            color: #d4af37;
            font-size: 24px;
            padding: 0 10px;
        }

        /* Certificate content with classic spacing */
        .cert-text {
            font-size: 16px;
            line-height: 1.8;
            color: #2c1810;
            margin: 5mm 0;
            text-align: center;
            font-style: italic;
        }

        .researcher-name {
            font-size: 28px;
            font-weight: bold;
            color: #8b4513;
            margin: 4mm 0;
            text-align: center;
            letter-spacing: 1px;
            text-decoration: underline;
            text-decoration-color: #d4af37;
            text-underline-offset: 3px;
        }

        /* Classic details in formal table style */
        .details-section {
            margin-top: 8mm;
            padding: 5mm;
            border: 1px solid #d4af37;
            background: rgba(212, 175, 55, 0.05);
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
        }

        .details-table td {
            padding: 2mm 4mm;
            border-bottom: 1px dotted #d4af37;
            font-size: 12px;
        }

        .details-table td:first-child {
            font-weight: bold;
            color: #8b4513;
            width: 40%;
        }

        .details-table td:last-child {
            color: #2c1810;
        }

        /* Classic footer with formal elements */
        .footer {
            margin-top: 10mm;
            padding-top: 5mm;
            border-top: 3px double #d4af37;
            text-align: center;
            position: relative;
        }

        .footer::before {
            content: '❦';
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: #fffef7;
            color: #d4af37;
            font-size: 24px;
            padding: 0 10px;
        }

        .verification-note {
            background: rgba(139, 69, 19, 0.05);
            border: 2px solid #8b4513;
            padding: 4mm;
            font-size: 11px;
            color: #8b4513;
            text-align: center;
            font-style: italic;
            margin-top: 3mm;
        }

        /* Classic seal placeholder */
        .seal {
            position: absolute;
            bottom: 20mm;
            right: 20mm;
            width: 60px;
            height: 60px;
            border: 3px solid #d4af37;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(212, 175, 55, 0.1) 0%, transparent 70%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            color: #8b4513;
            font-weight: bold;
            text-align: center;
            line-height: 1.2;
        }
    </style>
</head>

<body>
    <div class="certificate">
        <div class="inner-border">
            <!-- Classic ornamental corners -->
            <div class="corner-ornament top-left"></div>
            <div class="corner-ornament top-right"></div>
            <div class="corner-ornament bottom-left"></div>
            <div class="corner-ornament bottom-right"></div>

            <img class="watermark" src="{{ public_path('vendor/core/plugins/hall-of-fame/images/logo.png') }}"
                alt="">

            <!-- Classic Header -->
            <div class="header">
                <div class="company-name">{{ trans('plugins/hall-of-fame::certificates.certificate.company_name') }}
                </div>
                <div class="title">{{ trans('plugins/hall-of-fame::certificates.certificate.title') }}</div>
                <div class="subtitle">{{ trans('plugins/hall-of-fame::certificates.certificate.subtitle') }}</div>
            </div>

            <!-- Certificate Content -->
            <div class="cert-text">{{ trans('plugins/hall-of-fame::certificates.certificate.awarded_to') }}</div>

            <div class="researcher-name">{{ $certificate->researcher_name }}</div>

            <div class="cert-text">
                {{ trans('plugins/hall-of-fame::certificates.certificate.for_contribution') }}
                <strong style="color: #8b4513;">{{ $certificate->vulnerability_title }}</strong>
            </div>

            <!-- Classic Details Table -->
            <div class="details-section">
                <table class="details-table">
                    <tr>
                        <td>{{ trans('plugins/hall-of-fame::certificates.certificate.details.vulnerability') }}</td>
                        <td>{{ $certificate->vulnerability_title }}</td>
                    </tr>
                    <tr>
                        <td>{{ trans('plugins/hall-of-fame::certificates.certificate.details.type') }}</td>
                        <td>{{ $certificate->vulnerability_type }}</td>
                    </tr>
                    <tr>
                        <td>{{ trans('plugins/hall-of-fame::certificates.certificate.details.discovery') }}</td>
                        <td>{{ $certificate->discovery_date->format('M j, Y') }}</td>
                    </tr>
                    <tr>
                        <td>{{ trans('plugins/hall-of-fame::certificates.certificate.details.acknowledged') }}</td>
                        <td>{{ $certificate->acknowledgment_date->format('M j, Y') }}</td>
                    </tr>
                    <tr>
                        <td>{{ trans('plugins/hall-of-fame::certificates.certificate.details.certificate_id') }}</td>
                        <td>{{ $certificate->certificate_id }}</td>
                    </tr>
                </table>
            </div>

            <!-- Classic Footer -->
            <div class="footer">
                <!-- Dynamic PGP Signature Section -->
                @if (($settings['signature_required'] ?? false) && !empty($pgpSignature))
                    <div
                        style="background: rgba(139, 69, 19, 0.05); border: 2px solid #8b4513; padding: 4mm; margin-bottom: 3mm; font-style: italic;">
                        <div style="font-weight: bold; color: #8b4513; margin-bottom: 2mm;">
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
            </div>

            <!-- Dynamic QR Code Section -->
            @if (($settings['include_qr_code'] ?? false) && !empty($qrCodeUrl))
                <div style="position: absolute; bottom: 15mm; left: 15mm; text-align: center;">
                    <div
                        style="width: 50px; height: 50px; border: 3px solid #d4af37; margin: 0 auto 5px; display: flex; align-items: center; justify-content: center; font-size: 8px; background: radial-gradient(circle, rgba(212, 175, 55, 0.1) 0%, transparent 70%); color: #8b4513; font-weight: bold;">
                        {{ trans('plugins/hall-of-fame::certificates.certificate.qr_code') }}</div>
                    <div style="font-size: 6px; color: #8b4513;">
                        {{ trans('plugins/hall-of-fame::certificates.certificate.details.verify') }}</div>
                </div>
            @endif

            <!-- Classic seal -->
            <div class="seal">
                {{ trans('plugins/hall-of-fame::certificates.certificate.official_seal') }}
            </div>
        </div>
    </div>
</body>

</html>
