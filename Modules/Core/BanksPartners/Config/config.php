<?php

return [
    'name' => 'Core/BanksPartners',

    'entity_private_access' => true,

    'advanced_views' => true,

    /**
     * Always use lower name without custom characters, spaces, etc
     */
    'permissions' => [
        'core.bankspartners.browse',
        'core.bankspartners.create',
        'core.bankspartners.update',
        'core.bankspartners.destroy'
    ]

];
