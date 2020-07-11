<?php

namespace Modules\Platform\Core\Helper;

/**
 * Class ValidationHelper
 *
 * @package Modules\Platform\Core\Helper
 */
class ValidationHelper
{

    /**
     * Foreign keys validator
     *
     * @param $entity
     * @return array
     */
    public static function validateForeignKeys($entity)
    {
        $result = [];

        $foreignKeys = DatabaseHelper::getKeyColumnUsage($entity->table);

        foreach ($foreignKeys as $fk) {

            try {
                $fkValueCount = \DB::query()->from($fk->TABLE_NAME)
                    ->where($fk->COLUMN_NAME, $entity->id)
                    ->where('deleted_at', null)
                    ->count();

                if ($fkValueCount > 0) {
                    $result[$fk->TABLE_NAME] = $fkValueCount;
                }
            }Catch(\Exception $e){
                //In Case of no column of soft delete in related table
            }
        }

        return $result;
    }

}