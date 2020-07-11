<?php

return [
    'name' => 'Core/Games',

    'entity_private_access' => true,

    'advanced_views' => true,

    /**
     * Always use lower name without custom characters, spaces, etc
     */
    'permissions' => [
        'games.browse',
        'games.create',
        'games.update',
        'games.destroy',

        'games-types.browse',
        'games-types.create',
        'games-types.update',
        'games-types.destroy'
    ]
];
