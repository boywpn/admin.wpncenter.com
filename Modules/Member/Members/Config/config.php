<?php

return [
    'name' => 'Member/Members',

    'entity_private_access' => true,

    'advanced_views' => true,

    /**
     * Always use lower name without custom characters, spaces, etc
     */
    'permissions' => [
        'member.members.commission',
        'member.members.gamesconfig',
        'member.members.settings',
        'member.members.browse',
        'member.members.create',
        'member.members.update',
        'member.members.destroy',

        'member.members.banks.browse',
        'member.members.banks.create',
        'member.members.banks.update',
        'member.members.banks.destroy'
    ]
];
