<?php

return [
    'setup' => [

        /**
         * Module Name
         */
        'module_name' => 'ContactEmails',

        /**
         * Singular module Name
         */
        'singular_name' => 'ContactEmail',

        /**
         * If set to true. Visibility of records in module will be limited with "Assigned TO" field (User or Group)
         */
        'entity_private_access' => false,

        /**
         * Entities definition
         */
        'entity' => [
            'contact_request' => [

                /**
                 * Entity name
                 */
                'name' => 'ContactEmail',

                /**
                 * Entity database table name
                 */
                'table' => 'contact_email',

                /**
                 * main - Main entity of module.
                 * settings - Dictionary entity of module
                 */
                'type' => 'main',

                /**
                 * HasMorphOwner trait will be added to entity
                 */
                'ownable' => false,

                /**
                 * LogsActivity trait will be added to entity
                 */
                'activity' => false,

                /**
                 * Commentable trait will be added to entity
                 */
                'comments' => true,

                /**
                 * HasAttachment trait will be added to entity
                 */
                'attachments' => true,

                'properties' => [

                    /**
                     * Definition of section in show|create|edit
                     */
                    'information' => [

                        'email' => [
                            'type' => 'string',
                            'rules' => 'required|email',
                        ],
                        'is_default' => [
                            'type' => 'boolean',
                        ],

                        'is_active' => [
                            'type' => 'boolean',
                        ],

                        'is_marketing' => [
                            'type' => 'boolean',
                        ],

                        'contact_id' => [
                            'type' => 'manyToOne', // Relation Type
                            'relation' => 'contact', // relation name
                            'display_column' => 'full_name', // Visible field in form and show view
                            'belongs_to' => 'Contact' // Belongs To
                        ],
                    ],
                    'notes' => [
                        'notes' => ['type' => 'text'],
                    ]
                ]
            ],
        ]

    ]
];
