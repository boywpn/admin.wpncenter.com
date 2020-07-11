<?php

namespace Modules\Platform\Core\Datatable\Scope;

use Illuminate\Support\Facades\App;
use Modules\Platform\User\Entities\Group;
use Modules\Platform\User\Entities\User;
use Nwidart\Modules\Facades\Module;
use Yajra\DataTables\Contracts\DataTableScope;

/**
 * Class OwnableEntityScope
 * @package Modules\Platform\Core\Datatable\Scope
 */
class OwnableEntityScope implements DataTableScope
{
    private $user;

    private $moduleName;

    private $entity;

    public function __construct(User $user, $moduleName,$mainEntity)
    {
        $this->user = $user;
        $this->moduleName = $moduleName;
        $this->entity = $mainEntity;
    }

    public function apply($query)
    {
        $user = \Auth::user();

        $entity = new $this->entity();

        $module = Module::find($this->moduleName);
        $privateAccess = config($module->getLowerName() . '.entity_private_access');

        // Module has private access - scope records
        if ($privateAccess) {
            $query->where(function ($query) use ($user,$entity) {
                $query->where(function ($query) use ($user,$entity) {
                    $query->where("$entity->table.owned_by_type", Group::class)->whereIn("$entity->table.owned_by_id", $user->groups()->pluck('id')->toArray());
                })
                    ->orWhere(function ($query) use ($user,$entity) {
                        $query->where("$entity->table.owned_by_type", User::class)->where("$entity->table.owned_by_id", '=', $user->id);
                    })
                    ->orWhere(function ($query) use ($user, $entity){
                        $query->where("$entity->table.owned_by_type", '=', null);
                    });
            });
        }

        return $query;
    }
}
