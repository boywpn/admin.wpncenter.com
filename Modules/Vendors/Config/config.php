<?php

return [
    'name' => 'Vendors',

    'entity_private_access' => false,

    'advanced_views' => true,


    /**
     * Always use lower name without custom characters, spaces, etc
     */
    'permissions' => [
        'vendors.settings',
        'vendors.browse',
        'vendors.create',
        'vendors.update',
        'vendors.destroy'
    ]
];
