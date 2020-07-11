<?php

namespace Modules\Platform\Core\Datatable\Scope;

use Yajra\DataTables\Contracts\DataTableScope;

/**
 * Scope Activity By Logged User
 *
 * Class ActivityAccountScope
 * @package Modules\Platform\Core\Datatable\Scope
 */
class UserActivityScope implements DataTableScope
{
    private $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function apply($query)
    {
        $userId = $this->userId;
        $query->where(function ($query) use ($userId) {
            $query->where('subject_id', '=', $this->userId)->orWhere('causer_id', '=', $this->userId);
        });

        return $query;
    }
}
