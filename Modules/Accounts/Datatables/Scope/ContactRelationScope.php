<?php
/**
 * Created by PhpStorm.
 * User: jw
 * Date: 09.10.18
 * Time: 10:32
 */

namespace Modules\Accounts\Datatables\Scope;


use Modules\Platform\Core\Datatable\Scope\BasicRelationScope;

class ContactRelationScope extends BasicRelationScope
{

    private $relation;

    public function relation($relation, $whereCondition = null, $entityId = null )
    {
        $this->relation = $relation;
    }

    public function apply($query)
    {
        $query->whereIn('contacts.id', $this->relation->pluck('id')->toArray());
    }
}
