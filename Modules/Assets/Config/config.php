<?php

return [
    'name' => 'Assets',

    'entity_private_access' => false,

    'advanced_views' => true,

    /**
     * Always use lower name without custom characters, spaces, etc
     */
    'permissions' => [
        'assets.settings',
        'assets.browse',
        'assets.create',
        'assets.update',
        'assets.destroy'
    ]
];
