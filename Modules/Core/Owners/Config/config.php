<?php

return [
    'name' => 'Core/Owners',

    'entity_private_access' => true,

    'advanced_views' => true,

    /**
     * Always use lower name without custom characters, spaces, etc
     */
    'permissions' => [
        'owners.browse',
        'owners.create',
        'owners.update',
        'owners.destroy'
    ]
];
