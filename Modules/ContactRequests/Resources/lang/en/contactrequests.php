<?php

return [

    'module' => 'Contact Requests',
    'module_description' => 'Contact request from websites, apis and other applications',
    'delete' => 'Delete',
    'edit' => 'Edit',
    'create' => 'Create',
    'back' => 'Back',
    'details' => 'Details',
    'list' => 'Contact Requests list',
    'updated' => 'Contact request updated',
    'created' => 'Contact request created',

    'dict' => [
    ],

    'panel' => [
        'information' => 'Information',
        'notes' => 'Notes'
    ],

    'form' => [
        'first_name' => 'First name',
        'last_name'  => 'Last name',
        'organization_name' => 'Organization / Company',
        'phone_number' => 'Phone number',
        'email' => 'Email',
        'other_contact_method' => 'Other contact method',
        'custom_subject' => 'Subject',
        'contact_date' => 'Preferred contact date',
        'next_contact_date' => 'Next contact date',
        'status_id' => 'Status',
        'preferred_id' => 'Preferred contact',
        'contact_reason_id' => 'Contact reason',
        'notes' => 'Notes',
        'owned_by' => 'Assigned To',
        'status' => 'Status',
        'method' => 'Method',
        'reason' => 'Reason',

    ],

    'table' => [
        'first_name' =>'First name',
        'last_name' =>'Last name',
        'organization_name' =>'Company',
        'phone_number' => 'Phone number',
        'contact_date' => 'Contact date',
        'next_contact_date' => 'Next contact date',
        'method' => 'Contact method',
        'reason' => 'Contact reason',
        'status' => 'Status',

    ],

    'settings' => [
        'contactrequeststatus' => 'Request status',
        'preferredcontactmethod' => 'Preferred contact method',
        'contactreason' => 'Contact reason'
    ],

    'contactrequeststatus' => [
        'module' => 'Request status',
        'module_description' => 'Manage request status',
        'panel' => [
            'details' => 'Details'
        ],
        'form' => [
            'name' => 'Name'
        ]
    ],

    'preferredcontactmethod' => [
        'module' => 'Preferred contact method',
        'module_description' => 'Manage preferred contact method',
        'panel' => [
            'details' => 'Details'
        ],
        'form' => [
            'name' => 'Name'
        ]
    ],

    'contactreason' => [
        'module' => 'Contact reason',
        'module_description' => 'Manage contact reason',
        'panel' => [
            'details' => 'Details'
        ],
        'form' => [
            'name' => 'Name'
        ]
    ]


];
