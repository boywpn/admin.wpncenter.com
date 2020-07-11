<?php

namespace Modules\Invoices\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Platform\Core\Helper\SeederHelper;

class InvoicesDatabaseSeeder extends SeederHelper
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
            ['id' => 1, 'name' => 'Created'],
            ['id' => 2, 'name' => 'Cancel'],
            ['id' => 3, 'name' => 'Approved'],
            ['id' => 4, 'name' => 'Sent'],
            ['id' => 5, 'name' => 'Paid'],
        ];

        DB::table('invoices_dict_status')->truncate();

        $this->saveOrUpdate('invoices_dict_status', $dictValues);
    }
}
