<?php

return [
    'public_certificates' => 'Public Certificates',
    'certificate' => [
        'title' => 'Certificate of Recognition',
        'subtitle' => 'Security Research Excellence',
        'company_name' => 'WHO ZID IS',
        'page_title' => 'Certificate - Who Zid IS',
        'signed_page_title' => 'Signed Security Research Certificate',
        
        // Certificate content
        'awarded_to' => 'This certificate is hereby awarded to Security Researcher',
        'for_contribution' => 'for outstanding contribution to cybersecurity through responsible vulnerability disclosure',
        'digitally_signed' => 'This certificate has been digitally signed and verified for authenticity',
        'pgp_verified' => 'PGP Signature Verified',
        
        // Certificate details
        'details' => [
            'date' => 'Date',
            'certificate_id' => 'Certificate',
            'vulnerability' => 'Vulnerability',
            'type' => 'Type',
            'discovery' => 'Discovery',
            'acknowledged' => 'Acknowledged',
            'verify' => 'Verify',
            'researcher' => 'Researcher',
            'email' => 'Email',
        ],
        
        // Signature section
        'signature' => [
            'authorized_by' => 'Authorized by',
            'digital_signature' => 'Digital Signature',
            'verification_note' => 'This certificate can be verified using the provided verification URL or PGP signature.',
        ],
        
        // Security notices
        'security' => [
            'verification_required' => 'Certificate verification is required for authenticity',
            'pgp_key_info' => 'Verify with PGP Key',
            'certificate_hash' => 'Certificate Hash',
        ],
    ],

    // Controller messages
    'certificate_already_exists' => 'Certificate already exists for this report',
    'certificate_generated_successfully' => 'Certificate generated successfully',
    'failed_to_generate' => 'Failed to generate certificate',
    'bulk_generated_successfully' => 'Successfully generated :count certificates',
    'bulk_generation_failed' => 'Bulk generation failed',
    'certificate_regenerated_successfully' => 'Certificate regenerated successfully',
    'failed_to_regenerate' => 'Failed to regenerate certificate',
    'certificate_file_not_found' => 'Certificate file not found',
    'certificate_deleted_successfully' => 'Certificate deleted successfully',
    'failed_to_delete' => 'Failed to delete certificate',

    // Admin view translations
    'admin' => [
        'certificate_details' => 'Certificate Details',
        'back_to_list' => 'Back to List',
        'certificate_information' => 'Certificate Information',
        'certificate_id' => 'Certificate ID',
        'created_date' => 'Created Date',
        'status' => 'Status',
        'active' => 'Active',
        'inactive' => 'Inactive',
        'is_signed' => 'Is Signed',
        'yes' => 'Yes',
        'no' => 'No',
        'vulnerability_report' => 'Vulnerability Report',
        'title' => 'Title',
        'researcher' => 'Researcher',
        'email' => 'Email',
        'severity' => 'Severity',
        'n_a' => 'N/A',
        'published' => 'Published',
        'no_vulnerability_report' => 'No vulnerability report associated.',
        'description' => 'Description',
        'actions' => 'Actions',
        'download_pdf' => 'Download PDF',
        'verify_certificate' => 'Verify Certificate',
        'view_pdf' => 'View PDF',
        'issued_at' => 'Issued At',
        'pdf' => 'PDF',
        'signed' => 'Signed',
    ],

    // Public view translations
    'public' => [
        'hall_of_fame_certificates' => 'Hall of Fame Certificates',
        'browse_certificates_info' => 'Browse through security research certificates awarded to researchers who have contributed to cybersecurity through responsible vulnerability disclosure.',
        'certificate' => 'Certificate',
        'vulnerability' => 'Vulnerability',
        'researcher' => 'Researcher',
        'issued' => 'Issued',
        'severity' => 'Severity',
        'view_details' => 'View Details',
        'download_pdf' => 'Download PDF',
        'verify' => 'Verify',
        'no_certificates_available' => 'No certificates available',
        'no_certificates_issued' => 'No certificates have been issued yet.',
        'not_available' => 'N/A',
    ],
];
