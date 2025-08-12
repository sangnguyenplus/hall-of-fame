<!DOCTYPE html>
<html dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <title>{{ trans('plugins/hall-of-fame::certificates.certificate.signed_page_title') }}</title>
    <style>
        @page {
            /* Standard A4 margins */
            margin: 20mm;
            size: A4 landscape;
        }

        body {
            font-family: {{ app()->getLocale() == 'ar' ? '"Tahoma", "Arial Unicode MS"' : 'Arial' }}, sans-serif;
            margin: 0;
            padding: 0;
            background: white;
            color: #333;
            font-size: 6px;
            width: 100%;
            height: 100%;
            direction: {{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }};
        }

        .certificate {
            width: 100%;
            border: 3px solid #1a365d;
            position: relative;
            background: white;
            padding: 10px;
            box-sizing: border-box;
            page-break-inside: avoid;
        }

        .inner-border {
            border: 2px solid #2d5a87;
            /* Uniform breathing space so border doesn't crowd content */
            padding: 8mm;
            box-sizing: border-box;
            position: relative;
        }

        /* Watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 200px;
            height: 200px;
            opacity: 0.04;
            z-index: 1;
        }

        /* Stable table layout (DomPDF-friendly) */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        .meta-table td {
            vertical-align: middle;
        }

        .meta-table .logo-cell {
            width: 25%;
        }

        .meta-table .meta-cell {
            text-align: {{ app()->getLocale() == 'ar' ? 'left' : 'right' }};
            font-size: 11px;
            color: #444;
        }

        .meta-row {
            margin: 2px 0;
        }

        .details-table td {
            vertical-align: top;
            width: 50%;
            padding: 2mm 0;
        }

        .details-table .right {
            text-align: {{ app()->getLocale() == 'ar' ? 'left' : 'right' }};
        }

        .detail {
            font-size: 12px;
            margin: 1mm 0;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #1a365d;
            /* Avoid top margin collapse from first child */
            padding: 0 0 5mm 0;
            margin: 0 0 3mm 0;
            overflow: hidden;
        }

        .header * {
            margin-top: 0;
        }

        .subtitle {
            font-size: 6px;
            color: #506f9b;
            letter-spacing: 0.5px;
            margin-bottom: 2mm;
        }

        .logo {
            max-height: 50px;
            max-width: 100px;
        }

        .company-name {
            font-size: 18px;
            font-weight: 700;
            color: #1a365d;
            margin: 1mm 0 2mm 0;
            letter-spacing: 1px;
        }

        .title {
            font-size: 14px;
            font-weight: 700;
            color: #2d5a87;
            margin: 1mm 0;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .divider {
            width: 60px;
            height: 2px;
            background: linear-gradient(90deg, transparent, #2d5a87, transparent);
            margin: 3mm auto 0 auto;
        }

        .content {
            text-align: center;
            margin: 8mm 0;
            position: relative;
            z-index: 2;
        }

        .cert-text {
            font-size: 10px;
            margin: 3mm 0;
            color: #333;
        }

        .researcher-name {
            font-size: 20px;
            font-weight: 700;
            color: #1a365d;
            margin: 6mm 0;
            padding: 4mm 10mm;
            display: inline-block;
            border-radius: 3px;
            background: #fff;
            text-decoration: underline;
            text-decoration-color: #2d5a87;
        }

        /* PGP Verification Badge */
        .pgp-verified {
            background: #28a745;
            color: white;
            padding: 3px 12px;
            border-radius: 15px;
            font-size: 8px;
            font-weight: bold;
            display: inline-block;
            margin: 4mm 0;
            letter-spacing: 0.5px;
        }

        /* Signature section */
        .pgp-signature {
            background: #f8f9fa;
            border: 2px solid #28a745;
            border-radius: 8px;
            padding: 4mm;
            margin: 6mm auto;
            max-width: 80%;
            font-family: 'Courier New', monospace;
            font-size: 6px;
            white-space: pre-wrap;
            word-break: break-all;
            color: #333;
            max-height: 40mm;
            overflow-y: auto;
            direction: ltr;
            /* Keep PGP signature in LTR regardless of locale */
        }

        .signature-header {
            background: #28a745;
            color: white;
            padding: 2mm;
            margin: -4mm -4mm 2mm -4mm;
            border-radius: 6px 6px 0 0;
            font-size: 8px;
            font-weight: bold;
            text-align: center;
            direction: {{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }};
        }

        .footer {
            /* Use normal flow so the outer borders expand to contain footer content */
            position: relative;
            margin-top: 10mm;
            border-top: 2px solid #1a365d;
            padding-top: 6px;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 10px;
        }

        .footer-left {
            text-align: {{ app()->getLocale() == 'ar' ? 'right' : 'left' }};
            flex: 1;
        }

        .footer-center {
            text-align: center;
            flex: 1.4;
            font-size: 6px;
            line-height: 1.4;
        }

        .footer-right {
            text-align: {{ app()->getLocale() == 'ar' ? 'left' : 'right' }};
            flex: 1;
        }

        .signature-img {
            max-height: 35px;
            max-width: 80px;
        }

        .stamp-img {
            max-height: 45px;
            max-width: 45px;
        }

        .signature-line {
            width: 100px;
            height: 1px;
            background: #666;
            margin-top: 6px;
        }

        /* Certificate ID badge */
        .certificate-id-badge {
            position: absolute;
            top: 5mm;
            {{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 5mm;
            background: rgba(26, 54, 93, 0.1);
            border: 1px solid #1a365d;
            padding: 2mm;
            border-radius: 4px;
            font-size: 8px;
            color: #1a365d;
            z-index: 3;
        }

        /* Security verification note */
        .security-note {
            position: absolute;
            bottom: 2mm;
            left: 50%;
            transform: translateX(-50%);
            font-size: 6px;
            color: #666;
            text-align: center;
            z-index: 3;
        }

        /* Ornamental corners */
        .ornament {
            position: absolute;
            width: 15px;
            height: 15px;
            background: #1a365d;
            transform: rotate(45deg);
            z-index: 2;
        }

        .ornament.top-left {
            top: -7px;
            left: -7px;
        }

        .ornament.top-right {
            top: -7px;
            right: -7px;
        }

        .ornament.bottom-left {
            bottom: -7px;
            left: -7px;
        }

        .ornament.bottom-right {
            bottom: -7px;
            right: -7px;
        }

        /* RTL specific adjustments */
        @media screen and (max-width: 0) {
            /* This media query will never match, but helps with RTL debugging */
        }
    </style>
</head>

<body>
    <div class="certificate">
        <div class="inner-border">
            <!-- Ornamental corners -->
            <div class="ornament top-left"></div>
            <div class="ornament top-right"></div>
            <div class="ornament bottom-left"></div>
            <div class="ornament bottom-right"></div>

            <!-- Certificate ID Badge -->
            <div class="certificate-id-badge">
                <strong>{{ trans('plugins/hall-of-fame::certificates.certificate.details.certificate_id') }}:</strong>
                {{ $certificate->certificate_id }}
            </div>

            <!-- Watermark -->
            <img class="watermark" src="{{ public_path('vendor/core/plugins/hall-of-fame/images/logo.png') }}"
                alt="">

            <!-- Header Section -->
            <table class="top-table" style="width:100%; border-collapse:collapse; margin-bottom:6mm;">
                <tr>
                    <td class="logo-cell"
                        style="width:22%; vertical-align:middle; text-align:{{ app()->getLocale() == 'ar' ? 'right' : 'left' }};">
                        <img class="logo" src="{{ public_path('vendor/core/plugins/hall-of-fame/images/logo.png') }}"
                            alt="WHO ZID IS Logo">
                    </td>
                    <td class="header-cell" style="width:56%; text-align:center; vertical-align:middle;">
                        <div class="company-name" style="margin-top:0;">
                            {{ trans('plugins/hall-of-fame::certificates.certificate.company_name') }}</div>
                        <div class="title">{{ trans('plugins/hall-of-fame::certificates.certificate.title') }}</div>
                        <div class="subtitle" style="margin-bottom:1.5mm;">
                            {{ trans('plugins/hall-of-fame::certificates.certificate.subtitle') }}</div>
                        <div class="pgp-verified">✓
                            {{ trans('plugins/hall-of-fame::certificates.certificate.pgp_verified') }}</div>
                        <div class="divider"></div>
                    </td>
                    <td class="meta-cell"
                        style="width:22%; text-align:{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}; vertical-align:middle; font-size:11px; color:#444;">
                        <div class="meta-row" style="margin:2px 0;">
                            <strong>{{ trans('plugins/hall-of-fame::certificates.certificate.details.date') }}:</strong>
                            {{ $certificate->acknowledgment_date->format('M j, Y') }}
                        </div>
                        <div class="meta-row" style="margin:2px 0;">
                            <strong>{{ trans('plugins/hall-of-fame::certificates.certificate.details.certificate_id') }}:</strong>
                            {{ $certificate->certificate_id }}
                        </div>
                    </td>
                </tr>
            </table>

            <!-- Certificate Content -->
            <div class="content">
                <div class="cert-text">{{ trans('plugins/hall-of-fame::certificates.certificate.awarded_to') }}</div>

                <div class="researcher-name">
                    {{ preg_replace('/\\s*Security\\s*Researcher$/i', '', $certificate->researcher_name) }}
                </div>

                <div class="cert-text">{{ trans('plugins/hall-of-fame::certificates.certificate.for_contribution') }}
                </div>

                <!-- Vulnerability Description -->
                <div style="font-size: 9px; margin-top: 6mm; text-align: justify; padding: 0 10px;">
                    {{ $certificate->description }}
                </div>

                <!-- PGP Signature Section -->
                <div class="pgp-signature">
                    <div class="signature-header">
                        🔐 {{ trans('plugins/hall-of-fame::certificates.certificate.signature.digital_signature') }}
                    </div>
                    {{ $signature }}
                </div>

                <div style="font-size: 8px; color: #666; margin-top: 4mm;">
                    {{ trans('plugins/hall-of-fame::certificates.certificate.digitally_signed') }}
                </div>
            </div>

            <!-- Footer Section -->
            <div class="footer">
                <div class="footer-left">
                    <img class="signature-img"
                        src="{{ public_path('vendor/core/plugins/hall-of-fame/images/signature.png') }}"
                        alt="Signature">
                    <div class="signature-line"></div>
                    <div style="font-size: 8px; margin-top: 2px; color: #666;">
                        {{ trans('plugins/hall-of-fame::certificates.certificate.signature.authorized_by') }}
                    </div>
                </div>
                <div class="footer-center">
                    <div>
                        <strong>{{ trans('plugins/hall-of-fame::certificates.certificate.details.vulnerability') }}:</strong>
                        {{ $certificate->vulnerability_title }}</div>
                    <div><strong>{{ trans('plugins/hall-of-fame::certificates.certificate.details.type') }}:</strong>
                        {{ $certificate->vulnerability_type }}</div>
                    <div>
                        <strong>{{ trans('plugins/hall-of-fame::certificates.certificate.details.discovery') }}:</strong>
                        {{ $certificate->discovery_date->format('M j, Y') }}</div>
                    <div>
                        <strong>{{ trans('plugins/hall-of-fame::certificates.certificate.details.acknowledged') }}:</strong>
                        {{ $certificate->acknowledgment_date->format('M j, Y') }}</div>
                    <div><strong>{{ trans('plugins/hall-of-fame::certificates.certificate.details.verify') }}:</strong>
                        {{ url('/certificates/verify/' . $certificate->certificate_id) }}</div>
                </div>
                <div class="footer-right">
                    <img class="stamp-img" src="{{ public_path('vendor/core/plugins/hall-of-fame/images/stamp.png') }}"
                        alt="Official Stamp">
                </div>
            </div>

            <!-- Security Verification Note -->
            <div class="security-note">
                {{ trans('plugins/hall-of-fame::certificates.certificate.signature.verification_note') }}
            </div>
        </div>
    </div>
</body>

</html>
