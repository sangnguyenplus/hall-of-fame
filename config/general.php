<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Email Notifications
    |--------------------------------------------------------------------------
    |
    | Configure email settings for vulnerability report notifications.
    |
    */

    'emails' => [
        // Default BCC recipient for all vulnerability reports
        'bcc' => 'contact@botble.com',

        // Email subject prefix for vulnerability reports
        'notification_subject' => '[Security Report] New Vulnerability Report: ',

        // Default sender name
        'from_name' => 'Hall of Fame',

        // Default sender email address
        'from_address' => 'no-reply@whozidis.com',
    ],

    'pgp' => [
        'enabled' => false,
        'public_key' => null,
        'private_key' => null,
        'passphrase' => null,
        'sign_pdf' => false,
        'encrypt_pdf' => false,
    ],
];
