<?php

return [
    'name' => 'Hall of Fame',
    'description' => 'Security researchers who have helped improve our security',

    'vulnerability-reports' => [
        'name' => 'Vulnerability Reports',
        'create' => 'Create new report',
        'edit' => 'Edit report',
        'form' => [
            'title' => 'Title',
            'vulnerability_type' => 'Vulnerability Type',
            'endpoint' => 'Affected Endpoint',
            'description' => 'Description',
            'impact' => 'Impact',
            'steps_to_reproduce' => 'Steps to Reproduce',
            'suggested_fix' => 'Suggested Fix',
            'researcher_name' => 'Researcher Name',
            'researcher_email' => 'Researcher Email',
            'researcher_bio' => 'About the Researcher',
            'status' => 'Status',
        ],
    ],
];
