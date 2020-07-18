<?php

return [
    'name' => 'Report/TransferLogs',

    'entity_private_access' => true,

    'advanced_views' => true,

    /**
     * Always use lower name without custom characters, spaces, etc
     */
    'permissions' => [
        'report.transferlogs.settings',
        'report.transferlogs.browse',
        'report.transferlogs.create',
        'report.transferlogs.update',
        'report.transferlogs.destroy'
    ]
];
