<?php

return [
    'defaults' => [
        'wrapper_class' => 'form-group form-float',
        'wrapper_error_class' => 'has-error',
        'label_class' => 'form-label',
        'field_class' => 'form-control',
        'help_block_class' => 'help-block',
        'error_class' => 'text-danger',
        'required_class' => 'required',

        'checkbox' => [
            'wrapper_class' => 'form group form-float checkbox-wrapper',
            'field_class' => 'filled-in'
        ],


        // Override a class from a field.
        'file' => [
            'wrapper_class' => 'form-group form-float form-file',
            'label_class' => 'form-label',
            'field_class' => 'form-control',
        ],
        'dateType' => [
            'wrapper_class' => 'form-group form-float',
            'label_class' => 'form-label',
            'field_class' => 'form-control datepicker',
        ],
        'dateTimeType' => [
            'wrapper_class' => 'form-group form-float',
            'label_class' => 'form-label',
            'field_class' => 'form-control datetimepicker',
        ]
        //'radio'               => [
        //    'choice_options'  => [
        //        'wrapper'     => ['class' => 'form-radio'],
        //        'label'       => ['class' => 'form-radio-label'],
        //        'field'       => ['class' => 'form-radio-field'],
        //],
    ],
    // Templates
    'form' => 'laravel-form-builder::form',
    'text' => 'laravel-form-builder::text',
    'textarea' => 'laravel-form-builder::textarea',
    'button' => 'laravel-form-builder::button',
    'buttongroup' => 'laravel-form-builder::buttongroup',
    'radio' => 'laravel-form-builder::radio',
    'checkbox' => 'laravel-form-builder::checkbox',
    'select' => 'laravel-form-builder::select',
    'choice' => 'laravel-form-builder::choice',
    'repeated' => 'laravel-form-builder::repeated',
    'child_form' => 'laravel-form-builder::child_form',
    'collection' => 'laravel-form-builder::collection',
    'static' => 'laravel-form-builder::static',

    // Remove the laravel-form-builder:: prefix above when using template_prefix
    'template_prefix' => '',

    'default_namespace' => '',

    'custom_fields' => [
        'switch' => \Modules\Platform\Core\Fields\SwitchType::class,
        'dateType' => \Modules\Platform\Core\Fields\DateType::class,
        'dateTimeType' => \Modules\Platform\Core\Fields\DateTimeType::class,
        'manyToOne' => \Modules\Platform\Core\Fields\ManyToOneType::class,
        'simpleColorPicker' => \Modules\Platform\Core\Fields\SimpleColorPicker::class,
        'tags' => \Modules\Platform\Core\Fields\TagsType::class,
    ]
];
