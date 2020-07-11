<?php

namespace Modules\Accounts\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Platform\Core\Helper\SeederHelper;

class AccountsDatabaseSeeder extends SeederHelper
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $accounts_dict_industry = [
            ['id' => 1, 'name' => 'Communications'],
            ['id' => 2, 'name' => 'Technology'],
            ['id' => 3, 'name' => 'Government/Military'],
            ['id' => 4, 'name' => 'Manufacturing'],
            ['id' => 5, 'name' => 'Financial Service'],
            ['id' => 6, 'name' => 'IT Service'],
            ['id' => 7, 'name' => 'Education'],
            ['id' => 8, 'name' => 'Pharma'],
            ['id' => 9, 'name' => 'Real Estate'],
            ['id' => 10, 'name' => 'Consulting'],
            ['id' => 11, 'name' => 'Health Care'],
        ];

        DB::table('accounts_dict_industry')->truncate();

        $this->saveOrUpdate('accounts_dict_industry', $accounts_dict_industry);

        $accounts_dict_rating = [
            ['id' => 1, 'name' => 'Acquired'],
            ['id' => 2, 'name' => 'Active'],
            ['id' => 3, 'name' => 'Market failed'],
            ['id' => 4, 'name' => 'Project cancelled'],
            ['id' => 5, 'name' => 'Shut down'],
        ];

        DB::table('accounts_dict_rating')->truncate();

        $this->saveOrUpdate('accounts_dict_rating', $accounts_dict_rating);

        $accounts_dict_type = [
            ['id' => 1, 'name' => 'Vendor'],
            ['id' => 2, 'name' => 'Customer'],
            ['id' => 3, 'name' => 'Investor'],
            ['id' => 4, 'name' => 'Partner'],
            ['id' => 5, 'name' => 'Press'],
            ['id' => 6, 'name' => 'Prospect'],
            ['id' => 7, 'name' => 'Reseller'],
            ['id' => 8, 'name' => 'Distributor'],
            ['id' => 9, 'name' => 'Supplier'],
        ];

        DB::table('accounts_dict_type')->truncate();

        $this->saveOrUpdate('accounts_dict_type', $accounts_dict_type);
    }
}
