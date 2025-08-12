<?php

namespace Whozidis\HallOfFame\Services;

use Whozidis\HallOfFame\Models\Certificate;
use Whozidis\HallOfFame\Models\VulnerabilityReport;
use Whozidis\HallOfFame\Models\PgpKey;
use Botble\Media\Facades\RvMedia;
use Botble\Setting\Facades\Setting;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Dompdf\Dompdf;
use Dompdf\Options;

class CertificateService
{
    protected PgpService $pgpService;

    public function __construct(PgpService $pgpService)
    {
        $this->pgpService = $pgpService;
    }

    /**
     * Generate certificate for vulnerability report
     */
    public function generateCertificate(VulnerabilityReport $report): Certificate
    {
        $certificateId = Certificate::generateCertificateId();
        
        $certificate = new Certificate([
            'vulnerability_report_id' => $report->id,
            'certificate_id' => $certificateId,
            'researcher_name' => $report->researcher_name,
            'researcher_email' => $report->researcher_email,
            'vulnerability_title' => $report->title,
            'vulnerability_type' => $report->vulnerability_type,
            'discovery_date' => $report->created_at->toDateString(),
            'acknowledgment_date' => now()->toDateString(),
            'description' => $this->generateCertificateDescription($report),
        ]);

        $certificate->save();

        // Generate PDF
        $this->generatePdf($certificate);

        // Sign PDF if enabled
        if (setting('hall_of_fame_pgp_sign_pdf', false)) {
            $this->signCertificate($certificate);
        }

        return $certificate;
    }

    /**
     * Generate PDF certificate
     */
    public function generatePdf(Certificate $certificate): string
    {
        $html = $this->renderCertificateHtml($certificate);
        
    $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('debugPng', true);
        $options->set('debugKeepTemp', true);
    // Ensure local filesystem images under public/ are accessible
    $options->setChroot(public_path());
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $pdfContent = $dompdf->output();
        $filename = "certificate_{$certificate->certificate_id}.pdf";
        $path = "storage/certificates/{$filename}";
        $fullPath = public_path($path);

        // Ensure directory exists
        $directory = dirname($fullPath);
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        File::put($fullPath, $pdfContent);
        
        $certificate->update(['pdf_path' => $path]);
        
        return $path;
    }

    /**
     * Render certificate HTML
     */
    protected function renderCertificateHtml(Certificate $certificate): string
    {
        $templateStyle = Setting::get('hall_of_fame_certificate_template_style', 'professional');
        $templateName = $this->getTemplateForStyle($templateStyle);
        
        // Get all dynamic settings
        $settings = $this->getCertificateSettings();
        
        // Generate QR code if enabled
        $qrCodeUrl = $settings['include_qr_code'] ? $this->generateQrCode($certificate) : null;
        
        // Get PGP signature if required
        $pgpSignature = $settings['signature_required'] ? $this->getPgpSignature($certificate) : null;
        
        return View::make($templateName, [
            'certificate' => $certificate,
            'settings' => $settings,
            'logoUrl' => $this->getCertificateLogo(),
            'signatureUrl' => $this->getSignatureImage(),
            'backgroundUrl' => $this->getCertificateBackground(),
            'includeQrCode' => $settings['include_qr_code'],
            'qrCodeUrl' => $qrCodeUrl,
            'signatureRequired' => $settings['signature_required'],
            'pgpSignature' => $pgpSignature,
        ])->render();
    }

    /**
     * Get template name based on style
     */
    protected function getTemplateForStyle(string $style): string
    {
        $templates = [
            'professional' => 'plugins/hall-of-fame::certificates.template',
            'modern' => 'plugins/hall-of-fame::certificates.modern-template',
            'classic' => 'plugins/hall-of-fame::certificates.classic-template',
        ];

        return $templates[$style] ?? $templates['professional'];
    }

    /**
     * Sign certificate with PGP
     */
    public function signCertificate(Certificate $certificate): bool
    {
        $signingKey = PgpKey::getActiveSigningKey();
        
        if (!$signingKey || !$certificate->hasPdf()) {
            return false;
        }

        try {
            $pdfContent = File::get(public_path($certificate->pdf_path));
            $signature = $this->pgpService->signText($pdfContent, $signingKey);
            
            if ($signature) {
                $certificate->update([
                    'pgp_signature' => $signature,
                    'is_signed' => true,
                ]);

                // Create signed PDF with embedded signature
                $this->createSignedPdf($certificate, $signature);
                
                return true;
            }
        } catch (\Exception $e) {
            Log::error('Certificate signing failed: ' . $e->getMessage());
        }

        return false;
    }

    /**
     * Create signed PDF with embedded signature
     */
    protected function createSignedPdf(Certificate $certificate, string $signature): void
    {
        $html = $this->renderSignedCertificateHtml($certificate, $signature);
        
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $pdfContent = $dompdf->output();
        $filename = "certificate_{$certificate->certificate_id}_signed.pdf";
        $path = "storage/certificates/{$filename}";
        $fullPath = public_path($path);

        File::put($fullPath, $pdfContent);
        
        $certificate->update(['signed_pdf_path' => $path]);
    }

    /**
     * Render signed certificate HTML with embedded signature
     */
    protected function renderSignedCertificateHtml(Certificate $certificate, string $signature): string
    {
        return View::make('plugins/hall-of-fame::certificates.signed-template', [
            'certificate' => $certificate,
            'signature' => $signature,
            'logoUrl' => $this->getCertificateLogo(),
            'signatureUrl' => $this->getSignatureImage(),
            'backgroundUrl' => $this->getCertificateBackground(),
        ])->render();
    }

    /**
     * Generate certificate description
     */
    protected function generateCertificateDescription(VulnerabilityReport $report): string
    {
        return "This certificate acknowledges {$report->researcher_name} for responsibly " .
               "disclosing a {$report->vulnerability_type} vulnerability in our systems. " .
               "The reported issue has been verified, addressed, and resolved by our security team.";
    }

    /**
     * Get certificate logo URL
     */
    protected function getCertificateLogo(): string
    {
        $logoSetting = setting('hall_of_fame_certificate_logo');
        
        if ($logoSetting) {
            return RvMedia::getImageUrl($logoSetting);
        }
        
    // Use the plugin's PNG logo from local filesystem for Dompdf
    return public_path('vendor/core/plugins/hall-of-fame/images/logo.png');
    }

    /**
     * Get signature image URL
     */
    protected function getSignatureImage(): string
    {
        $signatureSetting = setting('hall_of_fame_certificate_signature');
        
        if ($signatureSetting) {
            return RvMedia::getImageUrl($signatureSetting);
        }
        
    // Use the plugin's PNG signature from local filesystem for Dompdf
    return public_path('vendor/core/plugins/hall-of-fame/images/signature.png');
    }

    /**
     * Get certificate background URL
     */
    protected function getCertificateBackground(): string
    {
        $backgroundSetting = setting('hall_of_fame_certificate_background');
        
        if ($backgroundSetting) {
            return RvMedia::getImageUrl($backgroundSetting);
        }
        
        // Use the plugin's background
        return asset('vendor/core/plugins/hall-of-fame/images/certificate-bg.svg');
    }

    /**
     * Verify certificate authenticity
     */
    public function verifyCertificate(string $certificateId): array
    {
        $certificate = Certificate::where('certificate_id', $certificateId)->first();
        
        if (!$certificate) {
            return [
                'valid' => false,
                'message' => 'Certificate not found',
            ];
        }

        $verificationData = [
            'valid' => true,
            'certificate_id' => $certificate->certificate_id,
            'researcher_name' => $certificate->researcher_name,
            'vulnerability_title' => $certificate->vulnerability_title,
            'discovery_date' => $certificate->discovery_date->format('Y-m-d'),
            'acknowledgment_date' => $certificate->acknowledgment_date->format('Y-m-d'),
            'is_signed' => $certificate->is_signed,
        ];

        if ($certificate->is_signed && $certificate->pgp_signature) {
            $verificationData['signature_valid'] = $this->verifyPgpSignature($certificate);
        }

        return $verificationData;
    }

    /**
     * Verify PGP signature
     */
    protected function verifyPgpSignature(Certificate $certificate): bool
    {
        if (!$certificate->hasPdf() || !$certificate->pgp_signature) {
            return false;
        }

        try {
            $pdfContent = File::get(public_path($certificate->pdf_path));
            return $this->pgpService->verifySignature($pdfContent, $certificate->pgp_signature);
        } catch (\Exception $e) {
            Log::error('Certificate signature verification failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get certificate statistics
     */
    public function getCertificateStats(): array
    {
        return [
            'total_certificates' => Certificate::count(),
            'signed_certificates' => Certificate::where('is_signed', true)->count(),
            'certificates_this_month' => Certificate::whereMonth('created_at', now()->month)
                                                  ->whereYear('created_at', now()->year)
                                                  ->count(),
            'unique_researchers' => Certificate::distinct('researcher_email')->count('researcher_email'),
        ];
    }

    /**
     * Bulk generate certificates for approved reports
     */
    public function bulkGenerateCertificates(): int
    {
        $reports = VulnerabilityReport::where('status', 'published')
                                    ->where('is_published', true)
                                    ->whereDoesntHave('certificate')
                                    ->get();

        $generated = 0;
        
        foreach ($reports as $report) {
            try {
                $this->generateCertificate($report);
                $generated++;
            } catch (\Exception $e) {
                Log::error("Failed to generate certificate for report {$report->id}: " . $e->getMessage());
            }
        }

        return $generated;
    }

    /**
     * Generate QR code for certificate verification
     */
    protected function generateQrCode(Certificate $certificate): ?string
    {
        try {
            // Generate verification URL
            $verificationUrl = url("/verify/certificate/{$certificate->certificate_id}");
            
            // For now, return the URL - in production you might want to use a QR library
            // to generate actual QR code image data
            return $verificationUrl;
        } catch (\Exception $e) {
            Log::error('Failed to generate QR code: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get PGP signature for certificate
     */
    protected function getPgpSignature(Certificate $certificate): ?string
    {
        try {
            if ($certificate->is_signed && $certificate->pgp_signature) {
                return $certificate->pgp_signature;
            }
            
            // If signature is required but not yet generated, create it
            if (Setting::get('hall_of_fame_certificate_signature_required', true)) {
                return $this->generatePgpSignature($certificate);
            }
            
            return null;
        } catch (\Exception $e) {
            Log::error('Failed to get PGP signature: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate PGP signature for certificate
     */
    protected function generatePgpSignature(Certificate $certificate): ?string
    {
        try {
            // Get active signing key
            $signingKey = PgpKey::getActiveSigningKey();
            if (!$signingKey) {
                Log::warning('No active signing key available for certificate signature');
                return null;
            }
            
            // Get certificate content for signing
            $content = $this->getCertificateContentForSigning($certificate);
            
            // Use PGP service to create signature
            $signature = $this->pgpService->signText($content, $signingKey);
            
            if ($signature) {
                // Save signature to certificate
                $certificate->update([
                    'pgp_signature' => $signature,
                    'is_signed' => true
                ]);
                
                return $signature;
            }
            
            return null;
        } catch (\Exception $e) {
            Log::error('Failed to generate PGP signature: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get certificate content for signing
     */
    protected function getCertificateContentForSigning(Certificate $certificate): string
    {
        return implode('|', [
            $certificate->certificate_id,
            $certificate->researcher_name,
            $certificate->researcher_email,
            $certificate->vulnerability_title,
            $certificate->vulnerability_type,
            $certificate->discovery_date->format('Y-m-d'),
            $certificate->acknowledgment_date->format('Y-m-d'),
        ]);
    }

    /**
     * Get all certificate settings as array
     */
    public function getCertificateSettings(): array
    {
        return [
            'template_style' => Setting::get('hall_of_fame_certificate_template_style', 'professional'),
            'default_language' => Setting::get('hall_of_fame_certificate_language_default', 'en'),
            'watermark_opacity' => (float) Setting::get('hall_of_fame_certificate_watermark_opacity', 0.1),
            'auto_generate' => Setting::get('hall_of_fame_certificate_auto_generate', false),
            'include_qr_code' => Setting::get('hall_of_fame_certificate_include_qr_code', true),
            'signature_required' => Setting::get('hall_of_fame_certificate_signature_required', true),
            'batch_processing' => Setting::get('hall_of_fame_certificate_batch_processing', true),
            'export_formats' => Setting::get('hall_of_fame_certificate_export_formats', ['pdf']),
            'email_delivery' => Setting::get('hall_of_fame_certificate_email_delivery', false),
            'public_verification' => Setting::get('hall_of_fame_certificate_public_verification', true),
        ];
    }
}
