<?php

namespace Modules\Deals\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Platform\Core\Helper\SeederHelper;

class DealsDatabaseSeeder extends SeederHelper
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $deals_dict_business_type = [
            ['id' => 1, 'name' => 'Existing business'],
            ['id' => 2, 'name' => 'New business'],
        ];

        DB::table('deals_dict_business_type')->truncate();

        $this->saveOrUpdate('deals_dict_business_type', $deals_dict_business_type);

        $deals_dict_stage = [
            ['id' => 1, 'name' => 'Qualification'],
            ['id' => 2, 'name' => 'Needs analysis'],
            ['id' => 3, 'name' => 'Value proposition'],
            ['id' => 4, 'name' => 'Identify decision makers'],
            ['id' => 5, 'name' => 'Proposal price quote'],
            ['id' => 6, 'name' => 'Negotiation review'],
            ['id' => 7, 'name' => 'Closed won'],
            ['id' => 8, 'name' => 'Closed lost'],
            ['id' => 9, 'name' => 'Closed lost to competition'],
        ];

        DB::table('deals_dict_stage')->truncate();

        $this->saveOrUpdate('deals_dict_stage', $deals_dict_stage);
    }
}
