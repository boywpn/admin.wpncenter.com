<?php

return [
    'name' => 'Core/Boards',

    'entity_private_access' => true,

    'advanced_views' => true,

    /**
     * Always use lower name without custom characters, spaces, etc
     */
    'permissions' => [
        'core.boards.browse',
        'core.boards.create',
        'core.boards.update',
        'core.boards.destroy',

        'core.boards.users.browse',
        'core.boards.users.create',
        'core.boards.users.update',
        'core.boards.users.destroy',
    ]
];
