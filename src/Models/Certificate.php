<?php

namespace Whozidis\HallOfFame\Models;

use Botble\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends BaseModel
{
    protected $table = 'hof_certificates';

    protected $fillable = [
        'vulnerability_report_id',
        'certificate_id',
        'researcher_name',
        'researcher_email',
        'vulnerability_title',
        'vulnerability_type',
        'discovery_date',
        'acknowledgment_date',
        'description',
        'pdf_path',
        'signed_pdf_path',
        'pgp_signature',
        'is_signed',
        'is_encrypted',
        'metadata',
    ];

    protected $casts = [
        'discovery_date' => 'date',
        'acknowledgment_date' => 'date',
        'is_signed' => 'boolean',
        'is_encrypted' => 'boolean',
        'metadata' => 'array',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'discovery_date',
        'acknowledgment_date',
    ];

    public function vulnerabilityReport(): BelongsTo
    {
        return $this->belongsTo(VulnerabilityReport::class, 'vulnerability_report_id');
    }

    /**
     * Generate a unique certificate ID
     */
    public static function generateCertificateId(): string
    {
        do {
            $id = 'CERT-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
        } while (self::where('certificate_id', $id)->exists());

        return $id;
    }

    /**
     * Get the download URL for the certificate
     */
    public function getDownloadUrlAttribute(): string
    {
        return route('public.certificates.download', $this->certificate_id);
    }

    /**
     * Get the view URL for the certificate
     */
    public function getViewUrlAttribute(): string
    {
        return route('public.certificates.view', $this->certificate_id);
    }

    /**
     * Check if certificate has PDF file
     */
    public function hasPdf(): bool
    {
        return ! empty($this->pdf_path) && file_exists(public_path($this->pdf_path));
    }

    /**
     * Check if certificate has signed PDF file
     */
    public function hasSignedPdf(): bool
    {
        return ! empty($this->signed_pdf_path) && file_exists(public_path($this->signed_pdf_path));
    }
}
