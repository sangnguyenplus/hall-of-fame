<?php

namespace Whozidis\HallOfFame\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Whozidis\HallOfFame\Models\Certificate;
use Whozidis\HallOfFame\Models\VulnerabilityReport;
use Whozidis\HallOfFame\Services\CertificateService;
use Whozidis\HallOfFame\Tables\CertificateTable;

class CertificateController extends BaseController
{
    protected CertificateService $certificateService;

    public function __construct(CertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
    }

    public function index(CertificateTable $table)
    {
        return $table->renderTable();
    }

    public function show(int $id)
    {
        $certificate = Certificate::with('vulnerabilityReport')->findOrFail($id);

        return view('plugins/hall-of-fame::admin.certificates.show', compact('certificate'));
    }

    public function generate(int $reportId, BaseHttpResponse $response)
    {
        try {
            $report = VulnerabilityReport::findOrFail($reportId);
            
            // Check if certificate already exists
            if ($report->certificate) {
                return $response
                    ->setError()
                    ->setMessage('Certificate already exists for this report');
            }

            $certificate = $this->certificateService->generateCertificate($report);

            return $response
                ->setData(['certificate_id' => $certificate->certificate_id])
                ->setMessage('Certificate generated successfully');
        } catch (\Exception $e) {
            return $response
                ->setError()
                ->setMessage('Failed to generate certificate: ' . $e->getMessage());
        }
    }

    public function bulkGenerate(BaseHttpResponse $response)
    {
        try {
            $generated = $this->certificateService->bulkGenerateCertificates();

            return $response
                ->setMessage("Successfully generated {$generated} certificates");
        } catch (\Exception $e) {
            return $response
                ->setError()
                ->setMessage('Bulk generation failed: ' . $e->getMessage());
        }
    }

    public function regenerate(int $id, BaseHttpResponse $response)
    {
        try {
            $certificate = Certificate::findOrFail($id);
            $report = $certificate->vulnerabilityReport;

            // Delete old files
            if ($certificate->pdf_path && File::exists(public_path($certificate->pdf_path))) {
                File::delete(public_path($certificate->pdf_path));
            }
            if ($certificate->signed_pdf_path && File::exists(public_path($certificate->signed_pdf_path))) {
                File::delete(public_path($certificate->signed_pdf_path));
            }

            // Regenerate
            $this->certificateService->generatePdf($certificate);
            
            if (setting('hall_of_fame_pgp_sign_pdf', false)) {
                $this->certificateService->signCertificate($certificate);
            }

            return $response
                ->setMessage('Certificate regenerated successfully');
        } catch (\Exception $e) {
            return $response
                ->setError()
                ->setMessage('Failed to regenerate certificate: ' . $e->getMessage());
        }
    }

    public function download(string $certificateId)
    {
        $certificate = Certificate::where('certificate_id', $certificateId)->firstOrFail();
        
        $pdfPath = $certificate->hasSignedPdf() ? $certificate->signed_pdf_path : $certificate->pdf_path;
        
        if (!$pdfPath || !File::exists(public_path($pdfPath))) {
            abort(404, 'Certificate file not found');
        }

        $filename = "certificate_{$certificate->certificate_id}" . 
                   ($certificate->hasSignedPdf() ? '_signed' : '') . '.pdf';

        return response()->download(public_path($pdfPath), $filename);
    }

    public function view(string $certificateId)
    {
        $certificate = Certificate::where('certificate_id', $certificateId)->firstOrFail();
        
        $pdfPath = $certificate->hasSignedPdf() ? $certificate->signed_pdf_path : $certificate->pdf_path;
        
        if (!$pdfPath || !File::exists(public_path($pdfPath))) {
            abort(404, 'Certificate file not found');
        }

        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="certificate_' . $certificate->certificate_id . '.pdf"'
        ];

        return response()->file(public_path($pdfPath), $headers);
    }

    public function verify(string $certificateId)
    {
        Log::info("Certificate verify method called with ID: " . $certificateId);
        
        try {
            $verification = $this->certificateService->verifyCertificate($certificateId);
            Log::info("Verification result: " . json_encode($verification));
            
            return view('plugins/hall-of-fame::public.certificate-verify', compact('verification', 'certificateId'));
        } catch (\Exception $e) {
            Log::error("Error in verify method: " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());
            throw $e;
        }
    }

    public function verifyApi(string $certificateId, BaseHttpResponse $response)
    {
        $verification = $this->certificateService->verifyCertificate($certificateId);
        
        return $response->setData($verification);
    }

    public function publicIndex()
    {
        $certificates = Certificate::with('vulnerabilityReport')
                                 ->whereHas('vulnerabilityReport', function($query) {
                                     $query->where('is_published', true);
                                 })
                                 ->orderBy('created_at', 'desc')
                                 ->paginate(20);

        return view('plugins/hall-of-fame::public.certificates', compact('certificates'));
    }

    public function publicShow(string $certificateId)
    {
        $certificate = Certificate::where('certificate_id', $certificateId)
                                 ->whereHas('vulnerabilityReport', function($query) {
                                     $query->where('is_published', true);
                                 })
                                 ->firstOrFail();

        return view('plugins/hall-of-fame::public.certificate-details', compact('certificate'));
    }

    public function stats(BaseHttpResponse $response)
    {
        $stats = $this->certificateService->getCertificateStats();
        
        return $response->setData($stats);
    }

    public function destroy(int $id, BaseHttpResponse $response)
    {
        try {
            $certificate = Certificate::findOrFail($id);
            
            // Delete associated files
            if ($certificate->pdf_path && File::exists(public_path($certificate->pdf_path))) {
                File::delete(public_path($certificate->pdf_path));
            }
            if ($certificate->signed_pdf_path && File::exists(public_path($certificate->signed_pdf_path))) {
                File::delete(public_path($certificate->signed_pdf_path));
            }

            $certificate->delete();

            return $response
                ->setMessage('Certificate deleted successfully');
        } catch (\Exception $e) {
            return $response
                ->setError()
                ->setMessage('Failed to delete certificate: ' . $e->getMessage());
        }
    }
}
