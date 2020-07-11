<?php

namespace Modules\Vendors\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Platform\Core\Helper\SeederHelper;

class VendorsDatabaseSeeder extends SeederHelper
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
            ['id' => 1, 'name' => 'Outside services professional fees'],
            ['id' => 2, 'name' => 'Advertising marketing promotion'],
            ['id' => 3, 'name' => 'Rent and occupancy related'],
            ['id' => 4, 'name' => 'Supplies'],
            ['id' => 5, 'name' => 'Taxes and licenses'],
            ['id' => 6, 'name' => 'Employee fringe benefits'],
            ['id' => 7, 'name' => 'Utilities'],
            ['id' => 8, 'name' => 'Travel and entertainment'],
            ['id' => 9, 'name' => 'Insurance'],
            ['id' => 10, 'name' => 'Security'],
            ['id' => 11, 'name' => 'Auto'],
        ];

        DB::table('vendors_dict_category')->truncate();

        $this->saveOrUpdate('vendors_dict_category', $dictValues);
    }
}
