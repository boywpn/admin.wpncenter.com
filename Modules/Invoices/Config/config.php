<?php

return [
    'name' => 'Invoices',

    'entity_private_access' => false,

    'default_quantity' => 1,

    'advanced_views' => true,


    /**
     * Always use lower name without custom characters, spaces, etc
     */
    'permissions' => [
        'invoices.settings',
        'invoices.browse',
        'invoices.create',
        'invoices.update',
        'invoices.destroy'
    ]
];
