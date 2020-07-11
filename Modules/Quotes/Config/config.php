<?php

return [
    'name' => 'Quotes',

    'entity_private_access' => true,

    'default_quantity' => 1,

    'show_product_image' => true,

    'advanced_views' => true,

    /**
     * Always use lower name without custom characters, spaces, etc
     */
    'permissions' => [
        'quotes.settings',
        'quotes.browse',
        'quotes.create',
        'quotes.update',
        'quotes.destroy'
    ]
];
