<?php

namespace Modules\Platform\Companies\Datatables\Scope;

use HipsterJazzbo\Landlord\Facades\Landlord;
use Modules\Platform\User\Entities\Group;
use Modules\Platform\User\Entities\User;
use Nwidart\Modules\Facades\Module;
use Yajra\DataTables\Contracts\DataTableScope;

/**
 * Class CurrentCompanyScope
 * @package Modules\Platform\Companies\Datatable\Scope
 */
class CurrentCompanyScope implements DataTableScope
{

    public function apply($query)
    {
        $user = \Auth::user();

        if(!$user->hasPermissionTo('settings.access')){
            $query->where('company_id','=',Landlord::getTenants()->first());
        }

        return $query;
    }
}
