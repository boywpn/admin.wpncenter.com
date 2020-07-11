<?php

return [
    'name' => 'Payments',

    'entity_private_access' => true,

    'advanced_views' => true,

    /**
     * Always use lower name without custom characters, spaces, etc
     */
    'permissions' => [
        'payments.settings',
        'payments.browse',
        'payments.create',
        'payments.update',
        'payments.destroy'
    ]
];
