<?php

namespace Modules\Platform\Settings\Repositories;

use Modules\Platform\Core\Repositories\PlatformRepository;
use Modules\Platform\Settings\Entities\Currency;
use Modules\Platform\Settings\Entities\Language;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class CurrencyRepository
 * @package Modules\Platform\Settings\Repositories
 */
class CurrencyRepository extends PlatformRepository
{
    public function model()
    {
        return Currency::class;
    }
}
