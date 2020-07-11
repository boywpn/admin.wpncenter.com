<?php

return [
    'name' => 'Contacts',

    'entity_private_access' => true,

    'advanced_views' => true,

    'profile_picture_path' => 'storage/files/contact_profile/',
    'public_profile_picture_path' => 'files/contact_profile/',


    /**
     * Always use lower name without custom characters, spaces, etc
     */
    'permissions' => [
        'contacts.settings',
        'contacts.browse',
        'contacts.create',
        'contacts.update',
        'contacts.destroy'
    ]
];
