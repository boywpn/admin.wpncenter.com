<?php

return [
    'name' => 'Documents',

    'entity_private_access' => true,

    'advanced_views' => true,


    /**
     * Always use lower name without custom characters, spaces, etc
     */
    'permissions' => [
        'documents.settings',
        'documents.browse',
        'documents.create',
        'documents.update',
        'documents.destroy'
    ]
];
