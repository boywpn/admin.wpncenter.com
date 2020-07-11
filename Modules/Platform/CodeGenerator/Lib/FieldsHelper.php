<?php

namespace Modules\Platform\CodeGenerator\Lib;

use Stringy\Stringy;

/**
 * Field helper
 *
 * Class FieldsHelper
 * @package Modules\Platform\CodeGenerator\Lib
 */
class FieldsHelper
{

    /**
     * Prepare migration fields
     * @param $key
     * @param $prop
     * @return string
     */
    public static function migrationFiled($key, $prop)
    {
        if ($prop['type'] == 'ownedBy') {
            return "nullableMorphs('owned_by')";
        }
        if ($prop['type'] == 'string') {
            $result = "string('$key')";
        }
        if ($prop['type'] == 'text') {
            $result = "text('$key')";
        }
        if ($prop['type'] == 'integer') {
            $result = "integer('$key')";
        }
        if ($prop['type'] == 'email') {
            $result = "string('$key')";
        }
        if ($prop['type'] == 'decimal') {
            $result = "decimal('$key',10,2)";
        }
        if ($prop['type'] == 'manyToOne') {
            $result = "integer('$key')";
        }
        if ($prop['type'] == 'boolean') {
            $result = "boolean('$key')";
        }
        if ($prop['type'] == 'date') {
            $result = "date('$key')";
        }
        if ($prop['type'] == 'datetime') {
            $result = "dateTime('$key')";
        }

        return $result . '->nullable()';
    }

    /**
     * @param $name
     */
    public static function dateFieldName($name)
    {
        return Stringy::create($name)->camelize();
    }


    /**
     * @param $field
     * @return string
     */
    public static function getType($field)
    {
        $type = $field['type'];

        $result = 'text';

        switch ($type) {
            case 'ownedBy':
                $result = 'assigned_to';
                break;
            case 'string':
                $result = 'text';
                break;

            case 'manyToOne':
                $result = 'manyToOne';
                break;
            case 'date':
                $result = 'date';
                break;
            case 'integer':
                $result = 'number';
                break;
            case 'email':
                $result = 'email';
                break;
            case 'decimal':
                $result = 'decimal';
                break;
        }

        return $result;
    }
}
