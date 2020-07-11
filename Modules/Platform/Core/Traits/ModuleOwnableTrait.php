<?php

namespace Modules\Platform\Core\Traits;

use Modules\Platform\Core\Helper\EntityAccessHelper;

/**
 * Trait ModuleOwnableTrait
 *
 * @package Modules\Platform\Core\Traits
 */
trait ModuleOwnableTrait
{

    /**
     * Check if records in module should be scoped by access
     *
     * @return boolean
     */
    protected function scopedAccess()
    {
        return EntityAccessHelper::scopedAccess($this->moduleName, \Auth::user());
    }

    /**
     * Check if module is private access.
     * If Private access - check if user have access
     *
     * @return boolean
     */
    protected function blockEntityOwnableAccess()
    {
        return EntityAccessHelper::blockEntityOwnableAccess($this->moduleName, $this->entity, \Auth::user());
    }
}
