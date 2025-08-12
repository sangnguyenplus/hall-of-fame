<?php

return [
    // Plugin general
    'name' => 'Hall of Fame',
    'description' => 'Security researchers who have helped improve our security',
    'hall_of_fame_description' => 'Thank you to all the security researchers who have helped us keep our platform secure.',

    // Vulnerability Reports section
    'vulnerability_reports_name' => 'Vulnerability Reports',
    'vulnerability_reports_create' => 'Create new report',
    'vulnerability_reports_edit' => 'Edit report',
    'my_reports' => 'My Reports',
    'submit_vulnerability' => 'Submit a Vulnerability',

    // Form fields
    'form_title' => 'Title',
    'form_title_placeholder' => 'Enter report title',
    'form_vulnerability_type' => 'Vulnerability Type',
    'form_vulnerability_type_placeholder' => 'E.g. XSS, SQL Injection, CSRF',
    'form_endpoint' => 'Affected Endpoint',
    'form_endpoint_placeholder' => 'E.g. /api/users or https://example.com/contact',
    'form_description' => 'Description',
    'form_description_placeholder' => 'Describe the vulnerability in detail',
    'form_impact' => 'Impact',
    'form_impact_placeholder' => 'What is the potential impact of this vulnerability?',
    'form_steps_to_reproduce' => 'Steps to Reproduce',
    'form_steps_to_reproduce_placeholder' => 'Provide detailed steps to reproduce the vulnerability',
    'form_suggested_fix' => 'Suggested Fix',
    'form_suggested_fix_placeholder' => 'If you have suggestions on how to fix this issue',
    'form_attachments' => 'Attachments',
    'form_researcher_info' => 'Researcher Information',
    'form_researcher_name' => 'Researcher Name',
    'form_researcher_name_placeholder' => 'Your full name',
    'form_researcher_email' => 'Researcher Email',
    'form_researcher_email_placeholder' => 'Your email address',
    'form_researcher_bio' => 'About the Researcher',
    'form_researcher_bio_placeholder' => 'Brief description about yourself (optional)',
    'form_privacy_policy' => 'I have read and agree to the privacy policy',
    'form_submit' => 'Submit Report',
    'form_status' => 'Status',
    'form_status_info' => 'Status Information',
    'form_is_published' => 'Is Published',
    'form_admin_note' => 'Admin Note',
    'form_admin_note_placeholder' => 'Private note for admins only',

    // Researchers section
    'researchers_name' => 'Researchers',
    'researchers_create' => 'Create new researcher',
    'researchers_edit' => 'Edit researcher',

    // Status options
    'status_reported' => 'Reported',
    'status_validated' => 'Validated',
    'status_in_progress' => 'Fix in Progress',
    'status_fixed' => 'Fixed',
    'status_published' => 'Published',

    // Admin table columns
    'column_name' => 'Name',
    'column_email' => 'Email',
    'column_reports' => 'Reports',
    'column_created_at' => 'Created At',
    'column_updated_at' => 'Updated At',

    // Permissions
    'permission_vulnerability_reports' => 'Vulnerability Reports',
    'permission_create' => 'Create',
    'permission_edit' => 'Edit',
    'permission_delete' => 'Delete',

    // Messages
    'message_create_success' => 'Created successfully',
    'message_update_success' => 'Updated successfully',
    'message_delete_success' => 'Deleted successfully',
    'message_error' => 'Error',
    'approve_success' => 'Report has been approved and published successfully',
    'reject_success' => 'Report has been rejected',

    // Public pages
    'hall_of_fame' => 'Hall of Fame',
    'vulnerability_disclosure' => 'Vulnerability Disclosure',
    'recognition_program' => 'Recognition Program',
    'thank_you' => 'Thank You',
    'thank_you_message' => 'Thank you for submitting your vulnerability report. Our security team will review it as soon as possible.',
    'reference_id' => 'Reference ID',
    'view_hall_of_fame' => 'View Hall of Fame',
    'view_details' => 'View Details',
    'back_to_hall_of_fame' => 'Back to Hall of Fame',
    'reported_on' => 'Reported on',
    'no_reports_found' => 'No vulnerability reports found.',
    'no_reports_found_for_year' => 'No vulnerability reports found for :year.',
    
    // Submit page
    'Submit a Vulnerability' => 'Submit a Vulnerability',
    'Hall of Fame' => 'Hall of Fame',
    'Submission Guidelines' => 'Submission Guidelines',
    'Provide detailed information about the vulnerability, including steps to reproduce' => 'Provide detailed information about the vulnerability, including steps to reproduce',
    'Do not include any sensitive personal data of users or employees' => 'Do not include any sensitive personal data of users or employees',
    'Never attempt to access, modify, or delete data without permission' => 'Never attempt to access, modify, or delete data without permission',
    'Include screenshots or videos if they help explain the issue' => 'Include screenshots or videos if they help explain the issue',
    'Be respectful and patient as we investigate your report' => 'Be respectful and patient as we investigate your report',
    'For sensitive reports, consider <a href=":url" target="_blank">using our PGP key</a> to encrypt your submission' => 'For sensitive reports, consider <a href=":url" target="_blank">using our PGP key</a> to encrypt your submission',
    'Login Required' => 'Login Required',
    'You need to be logged in to submit a vulnerability report' => 'You need to be logged in to submit a vulnerability report',
    'Login' => 'Login',
    'Register' => 'Register',
    
    // Form attachments hint
    'form_attachments_hint' => 'Upload screenshots, videos, or other files that help demonstrate the vulnerability (max 10MB per file)',
    'anonymous' => 'Anonymous',

    // Certificate translations
    'certificate' => [
        'title' => 'Certificate of Recognition',
        'signed_title' => 'Verified Certificate',
        'subtitle' => 'Security Research Excellence',
        'signed_subtitle' => 'PGP Signed Security Research Recognition',
        'company_name' => 'Who Zid IS',
        'awarded_to' => 'This certificate is hereby awarded to Security Researcher',
        'signed_awarded_to' => 'This digitally signed certificate is presented to',
        'excellence_text' => 'for outstanding contribution to cybersecurity through responsible vulnerability disclosure',
        'signed_excellence_text' => 'for excellence in cybersecurity research and responsible disclosure',
        'pgp_verified' => '✓ PGP VERIFIED',
        'pgp_signature_header' => '🔐 PGP DIGITAL SIGNATURE',
        'vulnerability_label' => 'Vulnerability:',
        'type_label' => 'Type:',
        'discovery_label' => 'Discovery:',
        'acknowledged_label' => 'Acknowledged:',
        'signed_label' => 'Signed:',
        'verify_label' => 'Verify:',
        'certificate_id' => 'Certificate:',
        'certificate_id_label' => 'Certificate',
        'date_label' => 'Date:',
        'signature_line' => 'Who Zid IS Security Team',
        'signature_subtitle' => 'Digitally Signed Certificate',
        'verify_note' => 'Verify at: :url | PGP Signature Included',
    ],
];
