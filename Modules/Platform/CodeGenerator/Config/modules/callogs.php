<?php

return [
    'setup' => [

        /**
         * Module Name
         */
        'module_name' => 'CallLogs',

        /**
         * Singular module Name
         */
        'singular_name' => 'CallLog',

        /**
         * If set to true. Visibility of records in module will be limited with "Assigned TO" field (User or Group)
         */
        'entity_private_access' => false,

        /**
         * Entities definition
         */
        'entity' => [
            'company' => [

                /**
                 * Entity name
                 */
                'name' => 'CallLog',

                /**
                 * Entity database table name
                 */
                'table' => 'call_logs',

                /**
                 * main - Main entity of module.
                 * settings - Dictionary entity of module
                 */
                'type' => 'main',

                /**
                 * HasMorphOwner trait will be added to entity
                 */
                'ownable' => true,

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
                'attachments' => false,

                'properties' => [

                    /**
                     * Definition of section in show|create|edit
                     */
                    'information' => [

                        /**
                         * Entity field (key is a name of field)
                         * type - type of entity.
                         * supported types:
                         * - string    - text filed (Normal input)
                         * - integer   - Integer field (Number field)
                         * - text      - Textarea field
                         * - ownedBy   - Required if use ownable trait (Dropdown)
                         * - manyToOne - Dictionary    (Dropdown)
                         * - date      - Date field    (Calendar)
                         * - email     - text field    (Text with validation)
                         * - decimal   - Decimal field (Number field)
                         * - datetime  - Date with time (calendar with time)
                         * - boolean   - Checkbox
                         *
                         * rules - rules generated in request object
                         */
                        'subject' => [
                            'type' => 'string',
                            'rules' => 'required',
                        ],
                        'phone_number' => [
                            'type' => 'string',
                            'rules' => 'required',
                        ],
                        'duration' => [
                            'type' => 'string',
                            'rules' => 'required',
                        ],
                        'owned_by' => [
                            'type' => 'ownedBy'
                        ],
                        'call_date' => [
                            'type' => 'datetime'
                        ],
                        'related_id' => [
                            'type' => 'datetime'
                        ],
                        'direction_id' => [
                            'type' => 'manyToOne', // Relation Type
                            'relation' => 'directionType', // relation name
                            'display_column' => 'name', // Visible field in form and show view
                            'belongs_to' => 'DirectionType' // Belongs To
                        ],

                    ],
                    'notes' => [
                        'notes' => ['type' => 'text'],
                    ]
                ]
            ],

            'direction_type' => [
                'name' => 'DirectionType',
                'table' => 'call_logs_dict_direction',
                'type' => 'settings',

                'insert_data' => [
                    'incoming',
                    'outgoint',
                ],
                'properties' => [
                    'detail' => [
                        'name' => [
                            'type' => 'string',
                        ],
                    ],
                ]
            ],


        ]

    ]
];
