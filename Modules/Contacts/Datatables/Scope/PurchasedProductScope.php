<?php

namespace Modules\Contacts\Datatables\Scope;

use Modules\Platform\Core\Interfaces\CrudRelatedScope;
use Yajra\DataTables\Contracts\DataTableScope;

/**
 * Class PurchasedProductScope
 *
 * @package Modules\Contacts\Datatables\Scope
 */
class PurchasedProductScope implements DataTableScope, CrudRelatedScope
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
        $query->where('invoices.contact_id', '=', $this->entityId);
    }
}
