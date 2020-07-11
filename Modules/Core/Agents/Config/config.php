<?php

return [
    'name' => 'Core/Agents',

    'entity_private_access' => true,

    'advanced_views' => true,

    /**
     * Always use lower name without custom characters, spaces, etc
     */
    'permissions' => [
        'core.agents.settings',
        'core.agents.browse',
        'core.agents.create',
        'core.agents.update',
        'core.agents.destroy',
        'core.agents.shareconfig',
    ]
];
