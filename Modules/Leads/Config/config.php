<?php
return [
    'name' => 'Leads',

    'entity_private_access' => true,

    'advanced_views' => true,

    /**
     * Always use lower name without custom characters, spaces, etc
     */
    'permissions' => [
        'leads.settings',
        'leads.browse',
        'leads.create',
        'leads.update',
        'leads.destroy'
    ],



];
