<?php

namespace Modules\Platform\Settings\Repositories;

use Modules\Platform\Core\Repositories\PlatformRepository;
use Modules\Platform\Settings\Entities\Currency;
use Modules\Platform\Settings\Entities\Language;
use Modules\Platform\Settings\Entities\Tax;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class TaxRepository
 * @package Modules\Platform\Settings\Repositories
 */
class TaxRepository extends PlatformRepository
{
    public function model()
    {
        return Tax::class;
    }
}
