<?php

return [
    'name' => 'Campaigns',

    'entity_private_access' => true,

    'advanced_views' => true,


    /**
     * Always use lower name without custom characters, spaces, etc
     */
    'permissions' => [
        'campaigns.settings',
        'campaigns.browse',
        'campaigns.create',
        'campaigns.update',
        'campaigns.destroy'
    ]
];
