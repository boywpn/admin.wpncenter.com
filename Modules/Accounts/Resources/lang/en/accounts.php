<?php

return [

    'module' => 'Accounts',
    'module_description' => 'Accounts is a Company/Customer that can have Contacts, Tickets, Deals, Quotes, Orders, Invoices, Documents, Campaigns...',
    'delete' => 'Delete',
    'edit' => 'Edit',
    'create' => 'Create',
    'choose' => 'Choose account',
    'back' => 'Back',
    'details' => 'Details',
    'list' => 'Accounts list',
    'updated' => 'Accounts updated',
    'created' => 'Accounts created',
    'create_new' => 'Create Account',

    'dict' => [
        'vendor' => 'Vendor',
        'customer' => 'Customer',
        'investor' => 'Investor',
        'partner' => 'Partner',
        'press' => 'Press',
        'prospect' => 'Prospect',
        'reseller' => 'Reseller',
        'distributor' => 'Distributor',
        'supplier' => 'Supplier',
        'communications' => 'Communications',
        'technology' => 'Technology',
        'government_military' => 'Government/Military',
        'manufacturing' => 'Manufacturing',
        'financial_service' => 'Financial Service',
        'it_service' => 'IT Service',
        'pharma' => 'Pharma',
        'real_estate' => 'Real estate',
        'consulting' => 'Consulting',
        'education' => 'Education',
        'health_care' => 'Health Care',
        'active' => 'Active',
        'acquired' => 'Acquired',
        'market_failed' => 'Market failed',
        'project_cancelled' => 'Project cancelled',
        'shut_down' => 'Shut down',
    ],

    'panel' => [
        'information' => 'Basic information',
        'contact_data' => 'Contact Data',
        'address_information' => 'Address information',
        'notes' => 'Notes'
    ],

    'form' => [
        'name' => 'Account name',
        'tax_number' => 'Tax number',
        'website' => 'Website',
        'account_number' => 'Account number',
        'annual_revenue' => 'Annual revenue',
        'employees' => 'Employees',
        'account_type_id' => 'Type',
        'account_industry_id' => 'Industry',
        'account_rating_id' => 'Rating',
        'phone' => 'Phone',
        'email' => 'Email',
        'secondary_email',
        'fax' => 'Fax',
        'skype_id' => 'Skype ID',
        'street' => 'Street',
        'city' => 'City',
        'state' => 'State',
        'country' => 'Country',
        'zip_code' => 'Zip code',
        'notes' => 'Notes',
        'owned_by' => 'Assigned To',
        'secondary_email' =>'Secondary email'
    ],

    'tabs' => [
        'contacts' => 'Contacts',
        'tickets' => 'Tickets',
        'deals' => 'Deals',
        'quotes' => 'Quotes',
        'orders' => 'Orders',
        'invoices' => 'Invoices',
        'documents' => 'Documents',
        'campaigns' => 'Campaigns',
        'assets' => 'Assets',
        'servicecontracts' => 'Service Contracts',
        'calls' => 'Call Log',
    ],

    'table' => [
    ],

    'settings' => [
        'type' => 'Type',
        'rating' => 'Rating',
        'industry' => 'Industry'
    ],


    'type' => [
        'module' => 'Account type',
        'module_description' => 'Manage account type',
        'panel' => [
            'details' => 'Details'
        ],
        'form' => [
            'name' => 'Name'
        ]
    ],

    'rating' => [
        'module' => 'Rating type',
        'module_description' => 'Manage rating type',
        'panel' => [
            'details' => 'Details'
        ],
        'form' => [
            'name' => 'Name'
        ]
    ],

    'industry' => [
        'module' => 'Customer industry',
        'module_description' => 'Manage customer industry',
        'panel' => [
            'details' => 'Details'
        ],
        'form' => [
            'name' => 'Name'
        ]
    ]


];
