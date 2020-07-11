<?php

return [
    'name' => 'Deals',

    'entity_private_access' => true,

    'advanced_views' => true,


    /**
     * Always use lower name without custom characters, spaces, etc
     */
    'permissions' => [
        'deals.settings',
        'deals.browse',
        'deals.create',
        'deals.update',
        'deals.destroy'
    ]
];
