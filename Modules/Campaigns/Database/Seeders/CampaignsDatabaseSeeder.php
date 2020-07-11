<?php

namespace Modules\Campaigns\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Platform\Core\Helper\SeederHelper;

class CampaignsDatabaseSeeder extends SeederHelper
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $campaigns_dict_status = [
            ['id' => 1, 'name' => 'Planning','icon' => 'fa fa-pause-circle-o', 'color' => 'col-grey'],
            ['id' => 2, 'name' => 'Active','icon' => 'fa fa-play-circle', 'color' => 'col-green'],
            ['id' => 3, 'name' => 'Inactive','icon' => 'fa fa-stop-circle', 'color' => 'col-brown'],
            ['id' => 4, 'name' => 'Completed','icon' => 'fa fa-pie-chart', 'color' => 'col-light-green'],
            ['id' => 5, 'name' => 'Cancelled','icon' => 'fa fa-trash', 'color' => 'colo-red'],
        ];

        DB::table('campaigns_dict_status')->truncate();

        $this->saveOrUpdate('campaigns_dict_status', $campaigns_dict_status);

        $campaigns_dict_type = [
            ['id' => 1, 'name' => 'Conference'],
            ['id' => 2, 'name' => 'Webinar'],
            ['id' => 3, 'name' => 'Trade show'],
            ['id' => 4, 'name' => 'Public relations'],
            ['id' => 5, 'name' => 'Partners'],
            ['id' => 6, 'name' => 'Referral program'],
            ['id' => 7, 'name' => 'Advertisement'],
            ['id' => 8, 'name' => 'Banner ads'],
            ['id' => 9, 'name' => 'Direct mail'],
            ['id' => 10, 'name' => 'Telemarketing'],
            ['id' => 11, 'name' => 'Others'],
        ];

        DB::table('campaigns_dict_type')->truncate();

        $this->saveOrUpdate('campaigns_dict_type', $campaigns_dict_type);
    }
}
