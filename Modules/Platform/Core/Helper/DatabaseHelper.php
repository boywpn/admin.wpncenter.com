<?php

namespace Modules\Platform\Core\Helper;

/**
 * Class DatabaseHelper
 *
 * @package Modules\Platform\Core\Helper
 */
class DatabaseHelper
{

    /**
     * Get keys column usage
     *
     * @param $tableName
     * @return array
     */
    public static function getKeyColumnUsage($tableName)
    {

        $query = "select * from information_schema.KEY_COLUMN_USAGE where  referenced_table_name = '" . $tableName . "'";

        $results = \DB::select($query);

        return $results;
    }



}
