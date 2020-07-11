<?php

return [

    'module' => 'Documents',
    'module_description' => 'List of documents that are uploaded to CRM',
    'delete' => 'Delete',
    'edit' => 'Edit',
    'create' => 'Create',
    'create_new' => 'Create Document',
    'back' => 'Back',
    'details' => 'Details',
    'list' => 'Documents list',
    'updated' => 'Document updated',
    'created' => 'Document created',
    'dict' => [
        'approval' => 'Approval',
        'proposal' => 'Proposal',
        'quote'   =>  'Quote',
        'contract' => 'Contract',
        'invoice' => 'Invoice',
        'report' => 'Report',

        'new' => 'New',
        'inprogress' => 'In progress',
        'approved' => 'Approved',
        'closed'   => 'Closed',

        'internal' => 'Internal',
        'external' => 'External'
    ],

    'panel' => [
        'details' => 'Details',
        'notes' => 'Notes'
    ],

    'form' => [
        'title' => 'Title',
        'document_type_id' => 'Type',
        'document_status_id' => 'Status',
        'document_category_id' => 'Category',
        'save' => 'Save',
        'owned_by' => 'Owned By',
        'notes' => 'Notes',
        'category' => 'Category',
        'status' => 'Status',
        'type' => 'Type'
    ],

    'table' => [
        'name' => 'Name',
        'expense_date' => 'Expense Date',
        'amount' => 'Amount'

    ],

    'settings' => [
        'category' => 'Category',
        'status' => 'Status',
        'type' => 'Type',
    ],

    'category' => [
        'module' => 'Document category',
        'module_description' => 'Manage document category',
        'panel' => [
            'details' => 'Details'
        ],
        'form' => [
            'name' => 'Name'
        ],
    ],

    'status' => [
        'module' => 'Document status',
        'module_description' => 'Manage document status',
        'panel' => [
            'details' => 'Details'
        ],
        'form' => [
            'name' => 'Name'
        ],
    ],

    'type' => [
        'module' => 'Document type',
        'module_description' => 'Manage document type',
        'panel' => [
            'details' => 'Details'
        ],
        'form' => [
            'name' => 'Name'
        ],
    ],


];
