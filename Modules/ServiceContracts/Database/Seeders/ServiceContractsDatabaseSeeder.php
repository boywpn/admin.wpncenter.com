<?php

namespace Modules\ServiceContracts\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Platform\Core\Helper\SeederHelper;

class ServiceContractsDatabaseSeeder extends SeederHelper
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
            ['id' => 1, 'name' => 'Low'],
            ['id' => 2, 'name' => 'Normal'],
            ['id' => 3, 'name' => 'High'],
            ['id' => 4, 'name' => 'Urgent'],
        ];

        DB::table('service_contracts_dict_priority')->truncate();

        $this->saveOrUpdate('service_contracts_dict_priority', $dictValues);

        $dictValues = [
            ['id' => 1, 'name' => 'Undefined'],
            ['id' => 2, 'name' => 'In planning'],
            ['id' => 3, 'name' => 'On hold'],
            ['id' => 4, 'name' => 'Open'],
            ['id' => 5, 'name' => 'In progress'],
            ['id' => 6, 'name' => 'Wait for response'],
            ['id' => 7, 'name' => 'Closed'],
            ['id' => 8, 'name' => 'Archived'],
        ];

        DB::table('service_contracts_dict_status')->truncate();

        $this->saveOrUpdate('service_contracts_dict_status', $dictValues);
    }
}
