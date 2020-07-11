<?php

namespace Modules\Orders\Database\Seeders;

use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Orders\Entities\Order;
use Modules\Orders\Entities\OrderRow;
use Modules\Platform\Core\Helper\SeederHelper;

class OrdersDemoSeederTableSeeder extends SeederHelper
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Order::truncate();
        OrderRow::truncate();

        \Auth::attempt(['email' => config('bap.demo_company_1'), 'password' => config('bap.demo_company_pass_1')]);

        for ($i = 1; $i <= 20; $i++) {
            $faker = Factory::create();

            $order = new Order();
            $order->id = $i;
            $order->changeOwnerTo(\Auth::user());
            $order->order_number = rand(1000000, 3000000);

            $order->order_date = $faker->dateTimeBetween(Carbon::now()->subMonth(1), Carbon::now()->addMonth(2))->format('Y-m-d H:i:s');
            $order->due_date = Carbon::now()->addMonth(3);

            $order->carrier_number = rand(10000, 20000);
            $order->purchase_order = rand(200000, 399999);

            $order->terms_and_cond = $faker->sentence();

            $order->notes = $faker->sentence();

            $order->bill_street = $faker->streetAddress;
            $order->bill_city =  $faker->city;
            $order->bill_state = 'DC';
            $order->bill_country = $faker->country;
            $order->bill_zip_code = $faker->postcode;


            $order->ship_street = $faker->streetAddress;
            $order->ship_city =  $faker->city;
            $order->ship_state = 'DC';
            $order->ship_country = $faker->country;
            $order->ship_zip_code = $faker->postcode;

            $order->order_status_id = rand(1, 4);
            $order->order_carrier_id = rand(1, 4);


            $order->account_id = rand(1, 20);

            $order->company_id = $this->firstCompany();

            $order->discount = rand(100, 200);
            $order->currency_id = 96; //US
            $order->tax_id = rand(1, 3);
            $order->paid = rand(100, 300);
            $order->delivery_cost = rand(100, 300);

            $order->save();

            for ($j = 0; $j <= 3 ; $j++) {
                $row = new OrderRow();
                $row->order_id = $order->id;
                $row->product_name = '#Product '.$faker->company;
                $row->price = rand(100, 1000);
                $row->quantity = rand(1, 5);
                $row->company_id = $this->firstCompany();

                $row->save();
            }
        }

        \Auth::attempt(['email' => config('bap.demo_company_2'), 'password' => config('bap.demo_company_pass_2')]);

        for ($i = 21; $i <= 50; $i++) {
            $faker = Factory::create();

            $order = new Order();
            $order->id = $i;
            $order->changeOwnerTo(\Auth::user());
            $order->order_number = rand(1000000, 3000000);

            $order->order_date = $faker->dateTimeBetween(Carbon::now()->subMonth(1), Carbon::now()->addMonth(2))->format('Y-m-d H:i:s');
            $order->due_date = Carbon::now()->addMonth(3);

            $order->carrier_number = rand(10000, 20000);
            $order->purchase_order = rand(200000, 399999);

            $order->terms_and_cond = $faker->sentence();

            $order->notes = $faker->sentence();

            $order->bill_street = $faker->streetAddress;
            $order->bill_city =  $faker->city;
            $order->bill_state = 'DC';
            $order->bill_country = $faker->country;
            $order->bill_zip_code = $faker->postcode;


            $order->ship_street = $faker->streetAddress;
            $order->ship_city =  $faker->city;
            $order->ship_state = 'DC';
            $order->ship_country = $faker->country;
            $order->ship_zip_code = $faker->postcode;

            $order->order_status_id = rand(1, 4);
            $order->order_carrier_id = rand(1, 4);


            $order->account_id = rand(21, 40);

            $order->company_id = $this->secondCompany();

            $order->discount = rand(100, 200);
            $order->currency_id = 96; //US
            $order->tax_id = rand(1, 3);
            $order->paid = rand(100, 300);
            $order->delivery_cost = rand(100, 300);

            $order->save();

            for ($j = 0; $j <= 3 ; $j++) {
                $row = new OrderRow();
                $row->order_id = $order->id;
                $row->product_name = '#Product '.$faker->company;
                $row->price = rand(100, 1000);
                $row->quantity = rand(1, 5);
                $row->company_id = $this->secondCompany();

                $row->save();
            }
        }
    }
}
