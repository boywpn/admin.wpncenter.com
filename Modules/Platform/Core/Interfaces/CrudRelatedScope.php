<?php

namespace Modules\Platform\Core\Interfaces;

/**
 * Interface CrudRelatedScope
 * @package Modules\Platform\Core\Interfaces
 */
interface CrudRelatedScope
{

    /**
     * @param $relation
     * @param $where
     * @param $whereType
     * @param $entityId
     * @return mixed
     */
    public function relation($relation, $where, $whereType,$entityId);
}
