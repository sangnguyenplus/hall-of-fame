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
];
