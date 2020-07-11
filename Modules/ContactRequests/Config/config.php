<?php

return [
    'name' => 'ContactRequests',

    'entity_private_access' => false,

    'advanced_views' => true,


    /**
     * Always use lower name without custom characters, spaces, etc
     */
    'permissions' => [
        'contactrequests.settings',
        'contactrequests.browse',
        'contactrequests.create',
        'contactrequests.update',
        'contactrequests.destroy'
    ]
];
