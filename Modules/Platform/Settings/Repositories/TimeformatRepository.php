<?php

namespace Modules\Platform\Settings\Repositories;

use Modules\Platform\Core\Repositories\PlatformRepository;
use Modules\Platform\User\Entities\TimeFormat;

/**
 * Class TimeformatRepository
 * @package Modules\Platform\Settings\Repositories
 */
class TimeformatRepository extends PlatformRepository
{
    public function model()
    {
        return TimeFormat::class;
    }
}
