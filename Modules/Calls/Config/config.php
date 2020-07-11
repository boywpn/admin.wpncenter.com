<?php

return [
    'name' => 'Calls',

    'entity_private_access' => false,

    'advanced_views' => true,

    /**
     * Always use lower name without custom characters, spaces, etc
     */
    'permissions' => [
        'calls.settings',
        'calls.browse',
        'calls.create',
        'calls.update',
        'calls.destroy'
    ]
];
