<?php

namespace Modules\Payments\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Platform\Core\Helper\SeederHelper;

class PaymentsDatatableSeeder extends SeederHelper
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
            ['id' => 1, 'name' => 'Submitted'],
            ['id' => 2, 'name' => 'Approved'],
            ['id' => 3, 'name' => 'Declined'],
        ];

        DB::table('payments_dict_status')->truncate();

        $this->saveOrUpdate('payments_dict_status', $statusData);

        $categoryData = [
            ['id' => 1, 'name' => 'Gas'],
            ['id' => 2, 'name' => 'Travel'],
            ['id' => 3, 'name' => 'Meals'],
            ['id' => 4, 'name' => 'Car rental'],
            ['id' => 5, 'name' => 'Cell phone'],
            ['id' => 6, 'name' => 'Groceries'],
            ['id' => 7, 'name' => 'Invoice'],
        ];

        DB::table('payments_dict_category')->truncate();

        $this->saveOrUpdate('payments_dict_category', $categoryData);

        $paymentMethodData = [
            ['id' => 1, 'name' => 'Cash'],
            ['id' => 2, 'name' => 'Cheque'],
            ['id' => 3, 'name' => 'Credit card'],
            ['id' => 4, 'name' => 'Direct debit'],
        ];

        DB::table('payments_dict_payment_method')->truncate();

        $this->saveOrUpdate('payments_dict_payment_method', $paymentMethodData);
    }
}
