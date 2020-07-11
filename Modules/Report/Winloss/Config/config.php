<?php

return [
    'name' => 'Report/Winloss',

    'entity_private_access' => true,

    'advanced_views' => true,

    /**
     * Always use lower name without custom characters, spaces, etc
     */
    'permissions' => [
        'report.winloss.browse',
    ]
];
