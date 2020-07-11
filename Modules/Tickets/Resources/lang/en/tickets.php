<?php

return [

    'module' => 'Tickets',
    'module_description' => 'Incidents, Support Requested, Feature Request and Issues',
    'delete' => 'Delete',
    'edit' => 'Edit',
    'create' => 'Create',
    'create_new' => 'Create Ticket',
    'back' => 'Back',
    'details' => 'Details',
    'list' => 'Tickets list',
    'updated' => 'Tickets updated',
    'created' => 'Tickets created',
    'choose' => 'Choose Ticket',

    'dict' => [
        'urgent' => 'Urgent',
        'critical' => 'Critical',
        'low' => 'Low',
        'normal' => 'Normal',
        'high' => 'High',
        'open' => 'Open',
        'in_progress' => 'In progress',
        'wait_for_response' => 'Wait for response',
        'closed' => 'Closed',
        'minor' => 'Minor',
        'major' => 'Major',
        'feature' => 'Feature',
        'big_problem' => 'Big problem',
        'small_problem' => 'Small problem',
        'other_problem' => 'Other problem',
        'new' => 'New'
    ],

    'panel' => [
        'information' => 'Basic information',
        'description' => 'Description',
        'resolution' => 'Resolution',
        'notes' => 'Notes'
    ],

    'form' => [
        'notes' => 'Notes',
        'resolution' => 'Resolution',
        'description' => 'Description',
        'owned_by' => 'Assigned To',
        'name' => 'Ticket name',
        'due_date' => 'Due date',
        'ticket_priority_id' => 'Priority',
        'ticket_status_id' => 'Status',
        'ticket_category_id' => 'Category',
        'ticket_severity_id' => 'Severity',
        'account_id' => 'Account',
        'contact_id' => 'Contact',
        'parent_id' => 'Parent Ticket',
        'category' => 'Category',
        'priority' => 'Priority',
        'severity' => 'Severity',
        'status' => 'Status',
        'account_name' => 'Account',
        'contact_name' => 'Contact'
    ],

    'table' => [
        'status' => 'Status',
        'category' => 'Category',
        'priority' => 'Priority',
        'severity' => 'Severity'
    ],

    'tabs' => [
        'tickets' => 'Child Tickets',
    ],

    'settings' => [
        'priority' => 'Priority',
        'status' => 'Status',
        'severity' => 'Severity',
        'category' => 'Category'
    ],


    'priority' => [
        'module' => 'Ticket priority',
        'module_description' => 'Manage ticket priority',
        'panel' => [
            'details' => 'Details'
        ],
        'form' => [
            'name' => 'Name'
        ],
    ],

    'status' => [
        'module' => 'Ticket status',
        'module_description' => 'Manage ticket status',
        'panel' => [
            'details' => 'Details'
        ],
        'form' => [
            'name' => 'Name'
        ],
    ],


    'severity' => [
        'module' => 'Ticket severity',
        'module_description' => 'Manage ticket severity',
        'panel' => [
            'details' => 'Details'
        ],
        'form' => [
            'name' => 'Name'
        ],
    ],


    'category' => [
        'module' => 'Ticket category',
        'module_description' => 'Manage ticket category',
        'panel' => [
            'details' => 'Details'
        ],
        'form' => [
            'name' => 'Name'
        ],
    ],



];
