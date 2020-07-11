<?php

return [
    'name' => 'Tickets',

    'entity_private_access' => true,

    'advanced_views' => true,


    /**
     * Always use lower name without custom characters, spaces, etc
     */
    'permissions' => [
        'tickets.settings',
        'tickets.browse',
        'tickets.create',
        'tickets.update',
        'tickets.destroy'
    ]
];
