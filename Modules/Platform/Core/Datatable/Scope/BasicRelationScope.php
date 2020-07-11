<?php

namespace Modules\Platform\Core\Datatable\Scope;

use Modules\Platform\Core\Http\Controllers\ModuleCrudRelationController;
use Modules\Platform\Core\Interfaces\CrudRelatedScope;
use Yajra\DataTables\Contracts\DataTableScope;

/**
 * Class BasicRelationScope
 * @package Modules\Platform\Core\Datatable\Scope
 */
class BasicRelationScope implements DataTableScope, CrudRelatedScope
{
    private $relation;

    private $whereCondition;

    private $whereType;

    private $entityId;

    public function relation($relation, $whereCondition = null, $whereType = null , $entityId = null)
    {
        $this->relation = $relation;
        $this->whereCondition = $whereCondition;
        $this->whereType = $whereType;
        $this->entityId = $entityId;
    }

    public function apply($query)
    {
        if(!empty($this->whereCondition)){
            if($this->whereType == ModuleCrudRelationController::WHERE_TYPE__COLUMN){
                $query->where($this->whereCondition, $this->entityId);
            }else{
                $query->whereIn($this->whereCondition, $this->relation->pluck('id')->toArray());
            }
        }else {
            $query->whereIn('id', $this->relation->pluck('id')->toArray());
        }
    }
}
