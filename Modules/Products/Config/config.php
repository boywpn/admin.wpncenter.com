<?php

return [
    'name' => 'Products',

    'entity_private_access' => false,

    'advanced_views' => true,

    /**
     * Always use lower name without custom characters, spaces, etc
     */
    'permissions' => [
        'products.settings',
        'products.browse',
        'products.create',
        'products.update',
        'products.destroy'
    ]
];
