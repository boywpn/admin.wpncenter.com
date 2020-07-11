<?php

namespace Modules\Platform\Settings\Repositories;

use Modules\Platform\Core\Repositories\PlatformRepository;
use Modules\Platform\Settings\Entities\Language;
use Modules\Platform\User\Entities\TimeZone;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class TimeZoneRepository
 * @package Modules\Platform\Settings\Repositories
 */
class TimeZoneRepository extends PlatformRepository
{
    public function model()
    {
        return TimeZone::class;
    }
}
