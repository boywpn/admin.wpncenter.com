<?php

namespace Modules\Platform\Account\Repositories;

use Modules\Platform\Core\Repositories\PlatformRepository;
use Modules\Platform\User\Entities\User;

/**
 * Class AccountRepository
 * @package Modules\Platform\Account\Repositories
 */
class AccountRepository extends PlatformRepository
{
    public function model()
    {
        return User::class;
    }
}
