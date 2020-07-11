<?php

return [
    'name' => 'Orders',

    'entity_private_access' => false,

    'default_quantity' => 1,

    'advanced_views' => true,


    /**
     * Always use lower name without custom characters, spaces, etc
     */
    'permissions' => [
        'orders.settings',
        'orders.browse',
        'orders.create',
        'orders.update',
        'orders.destroy'
    ]
];
