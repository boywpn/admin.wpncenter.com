<?php

namespace Modules\Orders\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Platform\Core\Helper\SeederHelper;

class OrdersDatabaseSeeder extends SeederHelper
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
            ['id' => 1, 'name' => 'FedEx'],
            ['id' => 2, 'name' => 'UPS'],
            ['id' => 3, 'name' => 'USPS'],
            ['id' => 4, 'name' => 'DHL'],
        ];

        DB::table('orders_dict_carrier')->truncate();

        $this->saveOrUpdate('orders_dict_carrier', $dictValues);

        $dictValues = [
            ['id' => 1, 'name' => 'Created'],
            ['id' => 2, 'name' => 'Approved'],
            ['id' => 3, 'name' => 'Delivered'],
            ['id' => 4, 'name' => 'Cancelled'],
        ];

        DB::table('orders_dict_status')->truncate();

        $this->saveOrUpdate('orders_dict_status', $dictValues);
    }
}
