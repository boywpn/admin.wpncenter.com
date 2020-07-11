<?php

namespace Modules\Platform\User\Repositories;

use Modules\Platform\Core\Repositories\PlatformRepository;
use Modules\Platform\User\Entities\Group;

/**
 * Class GroupRepository
 * @package Modules\Platform\User\Repositories
 */
class GroupRepository extends PlatformRepository
{
    public function model()
    {
        return Group::class;
    }
}
