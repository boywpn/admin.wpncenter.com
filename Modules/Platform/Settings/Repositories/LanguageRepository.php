<?php

namespace Modules\Platform\Settings\Repositories;

use Modules\Platform\Core\Repositories\PlatformRepository;
use Modules\Platform\Settings\Entities\Language;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class LanguageRepository
 * @package Modules\Platform\Settings\Repositories
 */
class LanguageRepository extends PlatformRepository
{
    public function model()
    {
        return Language::class;
    }
}
