<?php

return [
    'name' => 'Core/Banks',

    'entity_private_access' => true,

    'advanced_views' => true,

    /**
     * Always use lower name without custom characters, spaces, etc
     */
    'permissions' => [
        'core.banks.browse',
        'core.banks.create',
        'core.banks.update',
        'core.banks.destroy'
    ]
];
