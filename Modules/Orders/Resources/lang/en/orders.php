<?php

return [

    'module' => 'Orders',
    'module_description' => 'Order is confirmation document sent to the customer before delivering the goods or services.',
    'delete' => 'Delete',
    'edit' => 'Edit',
    'create' => 'Create',
    'create_new' => 'Create Order',
    'back' => 'Back',
    'details' => 'Details',
    'list' => 'Orders list',
    'updated' => 'Order updated',
    'created' => 'Order created',
    'convert_to_invoice' => 'Convert to Invoice',

    'copy_from_shipping_address' => 'Copy From Shipping Address',
    'copy_from_account' => 'Copy from Account',
    'copy_from_billing_address' => 'Copy From Billing Address',
    'print' => 'Print',

    'dict' => [
        'created' => 'Created',
        'cancel' => 'Cancel',
        'approved' => 'Approved',
        'delivered' => 'Delivered',
        'cancelled' => 'Cancelled'
    ],

    'panel' => [
        'information' => 'Basic information',
        'billing_address' => 'Billing address',
        'shipping_address' => 'Shipping address',
        'terms' => 'Terms and conditions',
        'notes' => 'Notes',
        'products_and_services' => 'Products & Services',
        'tax_and_currency' => 'Tax & Currency',
    ],

    'form' => [
        'order_number' => 'Order number',
        'carrier_number' => 'Carrier number',
        'deal_id' => 'Deal',
        'contact_id' => 'Contact',
        'customer_no' => 'Customer NO',
        'account_id' => 'Account',
        'purchase_order' => 'Purchase order',
        'due_date' => 'Due date',
        'order_date' => 'Order date',
        'order_status_id' => 'Status',
        'order_carrier_id' => 'Carrier',

        'bill_to' => 'Bill to',
        'bill_tax_number' => 'Tax number',
        'bill_street' => 'Bill street',
        'bill_country' => 'Bill country',
        'bill_state' => 'Bill state',
        'bill_city' => 'Bill city',
        'bill_zip_code' => 'Bill zip code',
        'ship_to' => 'Ship to',
        'ship_tax_number' => 'Tax number',
        'ship_street' => 'Ship street',
        'ship_country' => 'Ship country',
        'ship_state' => 'Ship state',
        'ship_city' => 'Ship city',
        'ship_zip_code' => 'Ship zip code',
        'terms_and_cond' => 'Terms and Conditions',
        'notes' => 'Notes',
        'owned_by' => 'Assigned To',

        'product_service' => 'Product / Service',
        'unit_cost' => 'Unit Cost',
        'quantity' => 'Quantity',
        'line_total' => 'Line Total',
        'subtotal' => 'Subtotal',
        'discount' => 'Discount',
        'tax' => 'Tax',
        'paid_to_date' => 'Paid to Date',
        'balance_due' => 'Balance Due',
        'tax_id' => 'Tax',
        'currency_id' =>  'Currency',
        'delivery_cost' => 'Delivery Cost',

        'paid' => 'Paid',
        'action' => 'Action',
        'add_row' => 'Add row',
        'gross_value' => 'Gross value',

        'tax_name' => 'Tax name',
        'currency_name' => 'Currency Name',
        'carrier' => 'Carrier',
        'status' => 'Status',
        'account_name' => 'Account',
        'contact_name' => 'Contact',
        'deal_name' => 'Deal'

    ],

    'table' => [
    ],

    'settings' => [
        'status' => 'Status',
        'carrier' => 'Carrier'
    ],


    'status' => [
        'module' => 'Status',
        'module_description' => 'Manage status',
        'panel' => [
            'details' => 'Details'
        ],
        'form' => [
            'name' => 'Name'
        ],
    ],


    'carrier' => [
        'module' => 'Carrier',
        'module_description' => 'Manage carrier',
        'panel' => [
            'details' => 'Details'
        ],
        'form' => [
            'name' => 'Name'
        ],
    ],

    'pdf' => [
        'paid' => 'Paid',
        'order_number' => 'Order',
        'order_date' => 'Order Date',
        'phone' => 'Phone',
        'fax' => 'Fax',
        'bill_to' => 'Bill To',
        'ship_to' => 'Ship To',
        'product_service' => 'Product / Service',
        'unit_cost' => 'Unit Cost',
        'quantity' => 'Quantity',
        'line_total' => 'Line Total',
        'subtotal' => 'Subtotal',
        'discount' => 'Discount',
        'delivery_cost' => 'Delivery Cost',
        'tax' => 'Tax',
        'tax_number' => 'Tax Number',
        'gross_value' => 'Gross Value',
        'notes' => 'Notes',
        'terms_and_cond' => 'Terms and Conditions',
        'invoice_number' => 'Invoice',
        'invoice_date' => 'Invoice Date',
        'due_date' => 'Due Date',
        'company' => 'Company',
        'shipping' => 'Shipping',
        'carrier' => 'Carrier',
        'carrier_number' => 'Carrier Number',
        'vat' => 'Tax Number',
    ]


];
