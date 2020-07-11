<?php

namespace Modules\Platform\Settings\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Platform\Core\Helper\CrudHelper;
use Modules\Platform\Core\Helper\SeederHelper;
use Modules\Platform\User\Entities\User;

/**
 * Class CompanySeeder
 * @package Modules\Platform\Settings\Database\Seeders
 */
class CompanySeeder extends SeederHelper
{

    private static $_COMPANIES = [
        [
            'id' => 1,
            'name' => 'OsCorp',
            'is_enabled' => 1,
            'description' => "Evil company that want to rule the World",
        ],
        [
            'id' => 2,
            'name' => 'Umbrella Corporation',
            'is_enabled' => 1,
            'description' => 'Our business is life itself...',
            'user_limit' => 50,
            'storage_limit' => 100
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Model::unguard();

        DB::table('bap_companies')->truncate();

        $this->saveOrUpdate('bap_companies', self::$_COMPANIES);
    }

}
