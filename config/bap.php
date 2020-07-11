<?php

return [

    /*
    |--------------------------------------------------------------------------
    | laravel-bap.com - global settings
    |--------------------------------------------------------------------------

    */

    'version' => env('APP_VERSION', '1.0'),

    'allow_registration' => env('BAP_ALLOW_REGISTRATION', false),

    /*
     * Attachments module validation
     */
    'file_upload_types' => 'jpe?g|png|pdf|zip|rar|doc?x',
    'file_upload_laravel_validation' => 'jpg,jpeg,png,pdf,zip,rar,doc,docx',
    'allowe_file_types_message' => 'Jpg, Jpeg, Png, Pdf, Zip, Rar, Doc, Docx',

    /*
     * because soft delete does not check foreign keys,
     * we added a special validation when deleting records. Note that foreign keys must be defined in the database
     * Validation is added In SettingsCrudController, ModuleCrudController
     * Example: (when deleting Language with id 1,  all users with language_id = 1 will be broken and view won't work)
     */
    'validate_fk_on_soft_delete' => true,

    /*
     * XSS Protection Middleware
     */
    'xss_protection_available_html_tags' => '<p><b><strike><blockquote><h1><h2><h3><h4><sup><sub><br><strong><i>',

    /*
     * Google analytics
     */
    'google_ga' => env('GOOGLE_GA', ''),

    'global_search' => false, // Not working yet

    /*
     * Demo instance configuration
     */
    'install_demo_data' => env('BAP_INSTALL_DEMO_DATA', false),
    'demo' => env('BAP_DEMO', false),
    'demo_login' => 'admin@laravel-bap.com',
    'demo_pass' => 'admin',

    'demo_company_1' => 'norman@laravel-bap.com',
    'demo_company_pass_1' => 'admin',

    'demo_company_2' => 'ada@laravel-bap.com',
    'demo_company_pass_2' => 'admin',

    /**
     * Set to true if only BAP Platform is installed
     * Set to false if BAP CRM is installed
     */
    'clean_platform' => env('CLEAN_BAP_PLATFORM', false),

    /**
     * Notifications
     */
    'comment_notification_enabled' => env('BAP_COMMENT_NOTIFICATION_ENABLED', true),
    'attachment_notification_enabled' => env('BAP_ATTACHMENT_NOTIFICATION_ENABLED', true),
    'record_assigned_notification_enabled' => env('BAP_RECORD_ASSIGNED_NOTIFICATION_ENABLED', true),

    /**
     * Gravatar ( Example in Contacts Module )
     */
    'gravatar_resize_width' => 150,
    'gravatar_resize_height' => 150,
    'gravatar_display_size' => 80,

    /**
     * https://en.gravatar.com/site/implement/images/
     *  404: do not load any image if none is associated with the email hash, instead return an HTTP 404 (File Not Found) response
     *  mp: (mystery-person) a simple, cartoon-style silhouetted outline of a person (does not vary by email hash)
     *  identicon: a geometric pattern based on an email hash
     *  monsterid: a generated 'monster' with different colors, faces, etc
     *  wavatar: generated faces with differing features and backgrounds
     *  retro: awesome generated, 8-bit arcade-style pixelated faces
     *  robohash: a generated robot with different colors, faces, etc
     *  blank: a transparent PNG image (border added to HTML below for demonstration purposes)
     */
    'gravatar_default_preview' => 'monsterid',
    'gravatar_local_cache' => true // store gravatar files as local files
];
