<?php

namespace Modules\Platform\Settings\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Platform\Core\Helper\CrudHelper;
use Modules\Platform\Core\Helper\SeederHelper;

/**
 * Class SettingsTimeFormatSeeder
 */
class TimeFormatSeeder extends SeederHelper
{
    private static $_TIME_FORMAT_DATA = array(
        ['id' => '1', 'name' => '24H', 'details' => 'H:i','js_details'=>'HH:mm'],
        ['id' => '2', 'name' => '12H', 'details' => 'H:i A','js_details'=>'h:mm A'],
    );

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('bap_time_format')->truncate();

        $this->saveOrUpdate('bap_time_format', self::$_TIME_FORMAT_DATA);
    }
}
