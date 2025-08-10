<?php

return [
    [
        'name' => 'Vulnerability reports',
        'flag' => 'vulnerability-reports.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'vulnerability-reports.create',
        'parent_flag' => 'vulnerability-reports.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'vulnerability-reports.edit',
        'parent_flag' => 'vulnerability-reports.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'vulnerability-reports.destroy',
        'parent_flag' => 'vulnerability-reports.index',
    ],
    [
        'name' => 'Approve',
        'flag' => 'vulnerability-reports.approve',
        'parent_flag' => 'vulnerability-reports.index',
    ],
    [
        'name' => 'Reject',
        'flag' => 'vulnerability-reports.reject',
        'parent_flag' => 'vulnerability-reports.index',
    ],
    [
        'name' => 'Settings',
        'flag' => 'hall-of-fame.settings',
        'parent_flag' => 'vulnerability-reports.index',
    ],

    // Researchers permissions
    [
        'name' => 'Researchers',
        'flag' => 'researchers.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'researchers.create',
        'parent_flag' => 'researchers.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'researchers.edit',
        'parent_flag' => 'researchers.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'researchers.destroy',
        'parent_flag' => 'researchers.index',
    ],
];
