<?php

return [
    'name' => 'Core/Promotions',

    'entity_private_access' => true,

    'advanced_views' => true,

    /**
     * Always use lower name without custom characters, spaces, etc
     */
    'permissions' => [
        'core.promotions.browse',
        'core.promotions.create',
        'core.promotions.update',
        'core.promotions.destroy'
    ]

];
