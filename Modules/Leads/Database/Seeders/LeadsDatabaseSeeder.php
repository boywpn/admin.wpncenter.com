<?php

namespace Modules\Leads\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Platform\Core\Helper\SeederHelper;

class LeadsDatabaseSeeder extends SeederHelper
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $statusData = [
            ['id' => 1, 'name' => 'New','icon' => 'fa fa-user-o', 'color' => 'col-orange'],
            ['id' => 2, 'name' => 'Contact in future','icon' => 'fa fa-calendar-o', 'color' => 'col-blue'],
            ['id' => 3, 'name' => 'Contacted','icon' => 'fa fa-handshake-o', 'color' => 'col-green'],
            ['id' => 4, 'name' => 'Junk lead','icon' => 'fa fa-trash', 'color' => 'col-grey'],
            ['id' => 5, 'name' => 'Lost lead','icon' => 'fa fa-chain-broken', 'color' => 'col-blue-grey'],
            ['id' => 6, 'name' => 'Not contacted','icon' => 'fa fa-question-circle', 'color' => 'col-deep-purple'],
            ['id' => 7, 'name' => 'Pre qualified','icon' => 'fa fa-meh-o', 'color' => 'col-light-green'],
        ];

        DB::table('leads_dict_status')->truncate();

        $this->saveOrUpdate('leads_dict_status', $statusData);

        $sourcedata = [
            ['id' => 1, 'name' => 'Advertisement'],
            ['id' => 2, 'name' => 'Cold call'],
            ['id' => 3, 'name' => 'Employee referral'],
            ['id' => 4, 'name' => 'External referral'],
            ['id' => 5, 'name' => 'Partner'],
            ['id' => 6, 'name' => 'Public relations'],
            ['id' => 7, 'name' => 'Trade show'],
            ['id' => 8, 'name' => 'Web form'],
            ['id' => 9, 'name' => 'Search engine'],
            ['id' => 10, 'name' => 'Facebook'],
            ['id' => 11, 'name' => 'Twitter'],
            ['id' => 12, 'name' => 'Online store'],
            ['id' => 13, 'name' => 'Seminar partner'],
            ['id' => 14, 'name' => 'Web download'],
        ];

        DB::table('leads_dict_source')->truncate();

        $this->saveOrUpdate('leads_dict_source', $sourcedata);

        $industrydata = [
            ['id' => 1, 'name' => 'Communucation'],
            ['id' => 2, 'name' => 'Technology'],
            ['id' => 3, 'name' => 'Government military'],
            ['id' => 4, 'name' => 'Manufacturing'],
            ['id' => 5, 'name' => 'Financial services'],
            ['id' => 6, 'name' => 'IT Service'],
            ['id' => 7, 'name' => 'Education'],
            ['id' => 8, 'name' => 'Pharma'],
            ['id' => 9, 'name' => 'Real Estate'],
            ['id' => 10, 'name' => 'Consulting'],
            ['id' => 11, 'name' => 'Health Care'],
            ['id' => 12, 'name' => 'RRP'],
            ['id' => 13, 'name' => 'Service provider'],
            ['id' => 14, 'name' => 'Data telecom'],
            ['id' => 15, 'name' => 'Large enterprise'],
        ];

        DB::table('leads_dict_industry')->truncate();

        $this->saveOrUpdate('leads_dict_industry', $industrydata);

        $ratingdata = [
            ['id' => 1, 'name' => 'Acquired'],
            ['id' => 2, 'name' => 'Active'],
            ['id' => 3, 'name' => 'Market failed'],
            ['id' => 4, 'name' => 'Project cancelled'],
            ['id' => 5, 'name' => 'Shut down'],
        ];

        DB::table('leads_dict_rating')->truncate();

        $this->saveOrUpdate('leads_dict_rating', $ratingdata);
    }
}
