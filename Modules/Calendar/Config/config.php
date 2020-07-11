<?php

return [
    'name' => 'Calendar',

    'entity_private_access' => true,

    'slotDuration' =>  '00:15:00',
    'snapDuration' =>  '00:15:00',

    /**
     * Always use lower name without custom characters, spaces, etc
     */
    'permissions' => [
        'calendar.settings',

        'calendar.browse',
        'calendar.create',
        'calendar.update',
        'calendar.destroy',

        'event.browse',
        'event.create',
        'event.update',
        'event.destroy'
    ]
];
