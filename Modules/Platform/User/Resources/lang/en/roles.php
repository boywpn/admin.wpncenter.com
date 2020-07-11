<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Module Translation
    |--------------------------------------------------------------------------
    */

    'module' => 'Roles',
    'module_description' => 'Manage User Roles & Permissions (Permissions can be updated in role details) ',
    'module_permissions_description'=>'Manage Permissions for selected role',

    'back' => 'Back',
    'create' => 'Create',
    'created' => 'Role Created',
    'updated' => 'Role Updated',
    'edit' => 'Edit',
    'details' => 'Details',
    'next' => 'Next',
    'prev' => 'Prev',
    'delete' => 'Delete',
    'setup_permissions'=>'Setup Permissions',
    'assign_permissions_to' => 'Assign Permissions to Role',
    'save_permissions'=>'Save permissions',
    'permissions_updated'=>'Permissions Updated. Check if everyting is ok',

    'permissions' => [
      'settings'=> [
          'access'=>'Settings Access'
      ]
    ],


    'table'=>[
        'name'=>'Name',
        'display_name'=>'Display Name',
        'guard_name'=>'Guard Name',
        'active'=>'Active',
        'created_at'=>'Created At',
        'updated_at'=>'Updated At',
    ],


    'panel' => [
        'details' => 'Details'
    ],

    'form' => [
        'name' => 'Name',
        'display_name' => 'Display Name',
        'guard_name' => 'Guard Name (most cases enter "web")',
        'save' => 'Save',
        'created_at' => 'Created At',
        'updated_at' => 'Updated At'
    ]
];
