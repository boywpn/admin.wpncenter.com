<?php

namespace Modules\Tickets\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Platform\Core\Helper\SeederHelper;

class TicketsDatabaseSeeder extends SeederHelper
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $dictValues = [
            ['id' => 1, 'name' => 'Big problem'],
            ['id' => 2, 'name' => 'Small problem'],
            ['id' => 3, 'name' => 'Other problem'],
        ];

        DB::table('tickets_dict_category')->truncate();

        $this->saveOrUpdate('tickets_dict_category', $dictValues);

        $dictValues = [
            ['id' => 1, 'name' => 'Low'],
            ['id' => 2, 'name' => 'Normal'],
            ['id' => 3, 'name' => 'High'],
            ['id' => 4, 'name' => 'Urgent'],
        ];

        DB::table('tickets_dict_priority')->truncate();

        $this->saveOrUpdate('tickets_dict_priority', $dictValues);


        $dictValues = [
            ['id' => 1, 'name' => 'Minor'],
            ['id' => 2, 'name' => 'Major'],
            ['id' => 3, 'name' => 'Feature'],
            ['id' => 4, 'name' => 'Critical'],
        ];

        DB::table('tickets_dict_severity')->truncate();

        $this->saveOrUpdate('tickets_dict_severity', $dictValues);


        $dictValues = [
            ['id' => 1, 'name' => 'New'],
            ['id' => 2, 'name' => 'In progress'],
            ['id' => 3, 'name' => 'Wait for response'],
            ['id' => 4, 'name' => 'Closed'],
        ];

        DB::table('tickets_dict_status')->truncate();

        $this->saveOrUpdate('tickets_dict_status', $dictValues);
    }
}
