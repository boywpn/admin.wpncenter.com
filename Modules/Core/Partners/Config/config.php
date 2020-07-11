<?php

return [
    'name' => 'Core/Partners',

    'entity_private_access' => true,

    'advanced_views' => true,

    /**
     * Always use lower name without custom characters, spaces, etc
     */
    'permissions' => [
        'partners.browse',
        'partners.create',
        'partners.update',
        'partners.destroy'
    ]
];
