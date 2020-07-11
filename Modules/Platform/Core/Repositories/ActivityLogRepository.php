<?php

namespace Modules\Platform\Core\Repositories;

use Spatie\Activitylog\Models\Activity;

/**
 * Class ActivityLogRepository
 * @package Modules\Platform\Core\Repositories
 */
class ActivityLogRepository extends PlatformRepository
{
    public function model()
    {
        return Activity::class;
    }
}
