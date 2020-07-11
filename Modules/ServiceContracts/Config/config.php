<?php

return [
    'name' => 'ServiceContracts',

    'entity_private_access' => true,

    'advanced_views' => true,


    /**
     * Always use lower name without custom characters, spaces, etc
     */
    'permissions' => [
        'servicecontracts.settings',
        'servicecontracts.browse',
        'servicecontracts.create',
        'servicecontracts.update',
        'servicecontracts.destroy'
    ]
];
