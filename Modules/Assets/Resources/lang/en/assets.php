<?php

return [

    'module' => 'Assets',
    'module_description' => 'Assets are unique (with tag or service number ) resources/ goods that are rendered to your Customers',
    'delete' => 'Delete',
    'edit' => 'Edit',
    'create' => 'Create',
    'create_new' => 'Create Asset',
    'back' => 'Back',
    'details' => 'Details',
    'list' => 'Assets list',
    'updated' => 'Assets updated',
    'created' => 'Assets created',


    'dict' => [
        'ready_to_deploy' => 'Read to deploy',
        'deployed' => 'Deployed',
        'pending' => 'Pending',
        'out_for_repair' => 'Out for repair',
        'out_for_diagnostics' => 'Out for diagnostics',
        'broken_not_fixable' => 'Broken/Not fixable',
        'lost_stolen' => 'Lost/Stolen',
        'on_order' => 'On order',
        'in_stock' => 'In stock',
        'phone' => 'Phone',
        'computer' => 'Computer',
        'license' => 'License',
        'car' => 'Car',

    ],

    'panel' => [
        'information' => 'Basic information',
        'notes' => 'Notes'
    ],

    'form' => [
        'name' => 'Name',
        'tag_number' => 'Tag number',
        'purchase_date' => 'Purchase date',
        'owned_by' => 'Assigned To',
        'model_no' => 'Model No',
        'asset_category_id' => 'Category',
        'supplier_id' => 'Supplier',
        'asset_status_id' => 'Status',
        'purchase_cost' => 'Purchase cost',
        'order_number' => 'Order number',
        'notes' => 'Notes',
        'asset_manufacturer_id' => 'Manufacturer',
        'contact_id' => 'Contact',
        'account_id' => 'Account',
        'contact_name' => 'Contact',
        'account_name' => 'Account',
        'status' => 'Status',
        'manufacturer' => 'Manufacturer',
        'created_at' => 'Created at',
        'updated_at' => 'Updated at',
        'category' => 'Category'
    ],

    'table' => [
    ],

    'settings' => [
        'status' => 'Status',
        'category' => 'Category',
        'manufacturer' => 'Manufacturer'
    ],

    'category' => [
        'module' => 'Category',
        'module_description' => 'Manage category',
        'panel' => [
            'details' => 'Details'
        ],
        'form' => [
            'name' => 'Name'
        ]
    ],

    'status' => [
        'module' => 'Status',
        'module_description' => 'Manage status',
        'panel' => [
            'details' => 'Details'
        ],
        'form' => [
            'name' => 'Name'
        ]
    ],

    'manufacturer' => [
        'module' => 'Manufacturer',
        'module_description' => 'Manage manufacturer',
        'panel' => [
            'details' => 'Details'
        ],
        'form' => [
            'name' => 'Name'
        ]
    ],




];
