<?php

return [
    'name' => 'Accounts',

    'entity_private_access' => true,

    'advanced_views' => true,

    /**
     * Always use lower name without custom characters, spaces, etc
     */
    'permissions' => [
        'accounts.settings',
        'accounts.browse',
        'accounts.create',
        'accounts.update',
        'accounts.destroy'
    ]
];
