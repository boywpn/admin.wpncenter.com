<?php

return [
    'name' => 'Core/Username',

    'entity_private_access' => true,

    'advanced_views' => true,

    /**
     * Always use lower name without custom characters, spaces, etc
     */
    'permissions' => [
        'core.username.browse',
        'core.username.create',
        'core.username.update',
        'core.username.destroy',
        'core.username.events',
        'core.username.betlimit',
    ]
];
