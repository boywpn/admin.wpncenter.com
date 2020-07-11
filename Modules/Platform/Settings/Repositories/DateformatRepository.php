<?php

namespace Modules\Platform\Settings\Repositories;

use Modules\Platform\Core\Repositories\PlatformRepository;
use Modules\Platform\User\Entities\DateFormat;

/**
 * Class DateformatRepository
 * @package Modules\Platform\Settings\Repositories
 */
class DateformatRepository extends PlatformRepository
{
    public function model()
    {
        return DateFormat::class;
    }
}
