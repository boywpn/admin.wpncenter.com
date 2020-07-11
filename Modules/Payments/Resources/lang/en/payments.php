<?php

return [

    'module' => 'Payments',
    'module_description' => 'List Payments & Expenses that are reported in CRM',
    'delete' => 'Delete',
    'edit' => 'Edit',
    'create' => 'Create',
    'create_new' => 'Create Payment',
    'back' => 'Back',
    'details' => 'Details',
    'list' => 'Payments list',
    'updated' => 'Payment updated',
    'created' => 'Payment created',
    'dict' => [
        'travel' => 'Travel',
        'cash' => 'Cash',
        'submitted' => 'Submitted',
        'uds' => 'USD',
        'gas' => 'Gas',
        'meals' => 'Meals',
        'car_rental' => 'Car Rental',
        'cell_phone' => 'Cell Phone',
        'cheque' => 'Cheque',
        'credit_card' => 'Credit Card',
        'direct_debit' => 'Direct Debit',
        'approved' => 'Approved',
        'declined' => 'Declined',
        'pln' => 'PLN',
        'eur' => 'EURO',
        'groceries' => 'Groceries',
        'invoice' => 'Invoice'
    ],

    'panel' => [
        'details' => 'Details',
    ],

    'form' => [
        'payment_category_id' => 'Category',
        'name' => 'Name',
        'payment_date' => 'Payment Date',
        'amount' => 'Amount',
        'payment_payment_method_id' => 'Payment method',
        'notes' => 'Notes',
        'payment_currency_id' => 'Currency',
        'payment_status_id' => 'Status',
        'save' => 'Save',
        'owned_by' => 'Owned By',
        'income' => 'Income',
        'currency' => 'Currency',
        'category' => 'Category',
        'status' => 'Status',
        'payment_method' => 'Payment Method'
    ],

    'table' => [
        'name' => 'Name',
        'payment_date' => 'Payment Date',
        'amount' => 'Amount'

    ],

    'settings' => [
        'category' => 'Category',
        'status' => 'Status',
        'currency' => 'Currency',
        'paymentmethod' => 'Payment method'
    ],

    'category' => [
        'module' => 'Payment category',
        'module_description' => 'Manage payment category',
        'panel' => [
            'details' => 'Details'
        ],
        'form' => [
            'name' => 'Name'
        ],
    ],

    'status' => [
        'module' => 'Payment status',
        'module_description' => 'Manage payment status',
        'panel' => [
            'details' => 'Details'
        ],
        'form' => [
            'name' => 'Name'
        ],
    ],

    'currency' => [
        'module' => 'Payment currency',
        'module_description' => 'Manage payment currency',
        'panel' => [
            'details' => 'Details'
        ],
        'form' => [
            'name' => 'Name'
        ],
    ],

    'paymentmethod' => [
        'module' => 'Payment payment method',
        'module_description' => 'Manage payment method',
        'panel' => [
            'details' => 'Details'
        ],
        'form' => [
            'name' => 'Name'
        ],
    ],



];
