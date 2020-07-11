<?php

namespace Modules\Calendar\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Platform\Core\Helper\SeederHelper;

class CalendarDatabaseSeeder extends SeederHelper
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $bap_calendar_dict_event_priority = [
            ['id' => 1, 'name' => 'Low'],
            ['id' => 2, 'name' => 'Normal'],
            ['id' => 3, 'name' => 'High'],
            ['id' => 4, 'name' => 'Urgent'],
        ];

        DB::table('bap_calendar_dict_event_priority')->truncate();

        $this->saveOrUpdate('bap_calendar_dict_event_priority', $bap_calendar_dict_event_priority);

        $bap_calendar_dict_event_status = [
            ['id' => 1, 'name' => 'New'],
            ['id' => 2, 'name' => 'In progress'],
            ['id' => 3, 'name' => 'On hold'],
            ['id' => 4, 'name' => 'Complete'],
        ];

        DB::table('bap_calendar_dict_event_status')->truncate();

        $this->saveOrUpdate('bap_calendar_dict_event_status', $bap_calendar_dict_event_status);
    }
}
