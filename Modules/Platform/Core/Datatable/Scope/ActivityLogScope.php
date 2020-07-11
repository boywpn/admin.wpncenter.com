<?php

namespace Modules\Platform\Core\Datatable\Scope;

use Yajra\DataTables\Contracts\DataTableScope;

/**
 * Class ActivityLogScope
 * @package Modules\Platform\Core\Datatable\Scope
 */
class ActivityLogScope implements DataTableScope
{
    private $modelType;

    private $entityId;

    public function __construct($modelType, $entityId)
    {
        $this->modelType = $modelType;
        $this->entityId =  $entityId;
    }

    public function apply($query)
    {
        $query->where('subject_type', '=', $this->modelType);
        $query->where('subject_id', '=', $this->entityId);
        $query->where('properties', '<>', '{"attributes":[],"old":[]}');

        return $query;
    }
}
