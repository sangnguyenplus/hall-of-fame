<?php

namespace Whozidis\HallOfFame\Http\Controllers;

use Botble\Base\Http\Controllers\BaseController;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Botble\Theme\Facades\Theme;
use Botble\Base\Facades\PageTitle;
use Botble\SeoHelper\Facades\SeoHelper;
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
                    ->setMessage(trans('plugins/hall-of-fame::certificates.certificate_already_exists'));
            }

            $certificate = $this->certificateService->generateCertificate($report);

            return $response
                ->setData(['certificate_id' => $certificate->certificate_id])
                ->setMessage(trans('plugins/hall-of-fame::certificates.certificate_generated_successfully'));
        } catch (\Exception $e) {
            return $response
                ->setError()
                ->setMessage(trans('plugins/hall-of-fame::certificates.failed_to_generate') . ': ' . $e->getMessage());
        }
    }

    public function bulkGenerate(BaseHttpResponse $response)
    {
        try {
            $generated = $this->certificateService->bulkGenerateCertificates();

            return $response
                ->setMessage(trans('plugins/hall-of-fame::certificates.bulk_generated_successfully', ['count' => $generated]));
        } catch (\Exception $e) {
            return $response
                ->setError()
                ->setMessage(trans('plugins/hall-of-fame::certificates.bulk_generation_failed') . ': ' . $e->getMessage());
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
                ->setMessage(trans('plugins/hall-of-fame::certificates.certificate_regenerated_successfully'));
        } catch (\Exception $e) {
            return $response
                ->setError()
                ->setMessage(trans('plugins/hall-of-fame::certificates.failed_to_regenerate') . ': ' . $e->getMessage());
        }
    }

    public function download(string $certificateId)
    {
        $certificate = Certificate::where('certificate_id', $certificateId)->firstOrFail();
        
        $pdfPath = $certificate->hasSignedPdf() ? $certificate->signed_pdf_path : $certificate->pdf_path;
        
        // If PDF doesn't exist, try to regenerate it
        if (!$pdfPath || !File::exists(public_path($pdfPath))) {
            try {
                Log::info("Certificate PDF missing for {$certificateId}, attempting to regenerate");
                $this->certificateService->generatePdf($certificate);
                
                // Refresh the certificate to get updated paths
                $certificate->refresh();
                $pdfPath = $certificate->hasSignedPdf() ? $certificate->signed_pdf_path : $certificate->pdf_path;
                
                if (!$pdfPath || !File::exists(public_path($pdfPath))) {
                    throw new \Exception('PDF regeneration failed');
                }
            } catch (\Exception $e) {
                Log::error("Failed to regenerate certificate PDF for {$certificateId}: " . $e->getMessage());
                abort(404, trans('plugins/hall-of-fame::certificates.certificate_file_not_found'));
            }
        }

        $filename = "certificate_{$certificate->certificate_id}" . 
                   ($certificate->hasSignedPdf() ? '_signed' : '') . '.pdf';

        return response()->download(public_path($pdfPath), $filename);
    }

    public function view(string $certificateId)
    {
        $certificate = Certificate::where('certificate_id', $certificateId)->firstOrFail();
        
        $pdfPath = $certificate->hasSignedPdf() ? $certificate->signed_pdf_path : $certificate->pdf_path;
        
        // If PDF doesn't exist, try to regenerate it
        if (!$pdfPath || !File::exists(public_path($pdfPath))) {
            try {
                Log::info("Certificate PDF missing for {$certificateId}, attempting to regenerate");
                $this->certificateService->generatePdf($certificate);
                
                // Refresh the certificate to get updated paths
                $certificate->refresh();
                $pdfPath = $certificate->hasSignedPdf() ? $certificate->signed_pdf_path : $certificate->pdf_path;
                
                if (!$pdfPath || !File::exists(public_path($pdfPath))) {
                    throw new \Exception('PDF regeneration failed');
                }
            } catch (\Exception $e) {
                Log::error("Failed to regenerate certificate PDF for {$certificateId}: " . $e->getMessage());
                abort(404, trans('plugins/hall-of-fame::certificates.certificate_file_not_found'));
            }
        }

        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="certificate_' . $certificate->certificate_id . '.pdf"'
        ];

        return response()->file(public_path($pdfPath), $headers);
    }

    public function verify(string $certificateId)
    {
        Theme::setLayout('hall-of-fame');
        
        // Set page title and SEO
        PageTitle::setTitle('Verify Certificate');
        SeoHelper::setTitle('Verify Certificate')
            ->setDescription('Verify the authenticity of certificate ' . $certificateId);

        // Set breadcrumbs  
        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(trans('plugins/hall-of-fame::vulnerability-reports.hall_of_fame'), route('public.hall-of-fame.index'))
            ->add(trans('plugins/hall-of-fame::certificates.public_certificates'), route('public.certificates.index'))
            ->add('Verify Certificate', route('public.certificates.verify', $certificateId));
        
        try {
            $verification = $this->certificateService->verifyCertificate($certificateId);
            
            return Theme::of('plugins/hall-of-fame::public.certificate-verify', compact('verification', 'certificateId'))->render();
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
        Theme::setLayout('hall-of-fame');
        
        // Set page title and SEO
        PageTitle::setTitle(trans('plugins/hall-of-fame::certificates.public_certificates'));
        SeoHelper::setTitle(trans('plugins/hall-of-fame::certificates.public_certificates'))
            ->setDescription(trans('plugins/hall-of-fame::certificates.public.browse_certificates_info'));

        // Set breadcrumbs  
        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(trans('plugins/hall-of-fame::vulnerability-reports.hall_of_fame'), route('public.hall-of-fame.index'))
            ->add(trans('plugins/hall-of-fame::certificates.public_certificates'), route('public.certificates.index'));
        
        $certificates = Certificate::with('vulnerabilityReport')
                                 ->whereHas('vulnerabilityReport', function($query) {
                                     $query->where('is_published', true);
                                 })
                                 ->orderBy('created_at', 'desc')
                                 ->paginate(20);

        return Theme::of('plugins/hall-of-fame::public.certificates', compact('certificates'))->render();
    }

    public function publicShow(string $certificateId)
    {
        Theme::setLayout('hall-of-fame');
        
        $certificate = Certificate::where('certificate_id', $certificateId)
                                 ->whereHas('vulnerabilityReport', function($query) {
                                     $query->where('is_published', true);
                                 })
                                 ->firstOrFail();

        // Set page title and SEO
        PageTitle::setTitle(trans('plugins/hall-of-fame::certificates.certificate.page_title'));
        SeoHelper::setTitle(trans('plugins/hall-of-fame::certificates.certificate.page_title'))
            ->setDescription('Certificate ' . $certificate->certificate_id);

        // Set breadcrumbs  
        Theme::breadcrumb()
            ->add(__('Home'), route('public.index'))
            ->add(trans('plugins/hall-of-fame::vulnerability-reports.hall_of_fame'), route('public.hall-of-fame.index'))
            ->add(trans('plugins/hall-of-fame::certificates.public_certificates'), route('public.certificates.index'))
            ->add('Certificate ' . $certificate->certificate_id, route('public.certificates.show', $certificate->certificate_id));

        return Theme::of('plugins/hall-of-fame::public.certificate-details', compact('certificate'))->render();
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
                ->setMessage(trans('plugins/hall-of-fame::certificates.certificate_deleted_successfully'));
        } catch (\Exception $e) {
            return $response
                ->setError()
                ->setMessage(trans('plugins/hall-of-fame::certificates.failed_to_delete') . ': ' . $e->getMessage());
        }
    }
}
