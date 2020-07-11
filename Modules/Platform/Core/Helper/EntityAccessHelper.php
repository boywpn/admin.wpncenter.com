<?php

namespace Modules\Platform\Core\Helper;

use Cog\Contracts\Ownership\Ownable;
use Modules\Platform\User\Entities\Group;
use Modules\Platform\User\Entities\User;
use Nwidart\Modules\Facades\Module;

/**
 * Entity Access Helper
 * Class EntityAccessHelper
 * @package Modules\Platform\Core\Helper
 */
class EntityAccessHelper
{

    /**
     * Check if access to entities should be limited by scope
     * @param $moduleName
     * @param null $user
     * @return bool
     */
    public static function scopedAccess($moduleName, $user = null)
    {
        if ($user == null) {
            $user = \Auth::user();
        }

        if ($moduleName != '') {
            $module = Module::find($moduleName);

            $privateAccess = config($module->getLowerName() . '.entity_private_access');

            if ($user->access_to_all_entity) {
                return false;
            }

            if ($privateAccess != null && $privateAccess) {
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * Check if user has access to module and entity
     * @param $moduleName
     * @param $entity
     * @param null $user
     * @return bool
     */
    public static function blockEntityOwnableAccess($moduleName, $entity, $user = null)
    {
        if ($user == null) {
            $user = \Auth::user();
        }

        if ($entity instanceof Ownable) {
            $module = \Module::find($moduleName);
            $privateAccess = config($module->getLowerName() . '.entity_private_access');

            if ($privateAccess != null && $privateAccess) {
                if ($entity->owner == null) {
                    return false;
                }
                if ($user->access_to_all_entity) {
                    return false;
                }

                if ($entity->owner instanceof Group) {
                    if (in_array($entity->owner->id, $user->groups()->pluck('id')->toArray())) {
                        return false;
                    }
                }
                if ($entity->owner instanceof User) {
                    if ($entity->owner->id == $user->id) {
                        return false;
                    }
                }
                return true;
            }
            return false;
        }

        return false;
    }
}
