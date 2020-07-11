<?php

namespace Modules\Invoices\Database\Seeders;

use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Invoices\Entities\Invoice;
use Modules\Invoices\Entities\InvoiceRow;
use Modules\Platform\Core\Helper\SeederHelper;
use Modules\Products\Entities\Product;

class InvoicesDemoSeederTableSeeder extends SeederHelper
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Invoice::truncate();
        InvoiceRow::truncate();

        \Auth::attempt(['email' => config('bap.demo_company_1'), 'password' => config('bap.demo_company_pass_1')]);


        for ($i = 1; $i <= 20; $i++) {
            $faker = Factory::create();

            $invoice = new Invoice();
            $invoice->id = $i;
            $invoice->changeOwnerTo(\Auth::user());
            $invoice->invoice_number = rand(1000000, 3000000);

            $invoice->invoice_date = $faker->dateTimeBetween(Carbon::now()->subMonth(1), Carbon::now()->addMonth(2))->format('Y-m-d H:i:s');
            $invoice->due_date = Carbon::now()->addMonth(3);

            $invoice->invoice_status_id = rand(1, 5);

            $invoice->terms_and_cond = $faker->sentence();

            $invoice->notes = $faker->sentence();

            $invoice->from_company = $faker->company;
            $invoice->from_tax_number = rand(8000000, 10000000);
            $invoice->from_street = $faker->streetAddress;
            $invoice->from_city =  $faker->city;
            $invoice->from_state = 'New York';
            $invoice->from_country = $faker->country;
            $invoice->from_zip_code = $faker->postcode;


            $invoice->bill_to = $faker->company;
            $invoice->bill_tax_number = rand(8000000, 10000000);
            $invoice->bill_street = $faker->streetAddress;
            $invoice->bill_city =  $faker->city;
            $invoice->bill_state = 'New York';
            $invoice->bill_country = $faker->country;
            $invoice->bill_zip_code = $faker->postcode;

            $invoice->ship_to = $faker->company;
            $invoice->ship_tax_number = rand(8000000, 10000000);
            $invoice->ship_street = $faker->streetAddress;
            $invoice->ship_city =  $faker->city;
            $invoice->ship_state = 'New York';
            $invoice->ship_country = $faker->country;
            $invoice->ship_zip_code = $faker->postcode;



            $invoice->account_id = rand(1, 20);

            $invoice->discount = rand(100, 200);
            $invoice->currency_id = 96; //US
            $invoice->tax_id = rand(1, 3);
            $invoice->paid = rand(100, 300);
            $invoice->delivery_cost = rand(100, 300);
            $invoice->account_number = $faker->bankAccountNumber;

            $invoice->company_id = $this->firstCompany();

            $invoice->save();

            for ($j = 0; $j <= 3 ; $j++) {

                $product = Product::find(rand(1,20));

                $row = new InvoiceRow();
                $row->invoice_id = $invoice->id;
                $row->product_name = '#Product '.$product->name;
                $row->price = $product->price;
                $row->quantity = rand(1, 5);
                $row->company_id = $this->firstCompany();
                $row->product_id = $product->id;

                $row->save();
            }
        }

        \Auth::attempt(['email' => config('bap.demo_company_2'), 'password' => config('bap.demo_company_pass_2')]);


        for ($i = 21; $i <= 40; $i++) {
            $faker = Factory::create();

            $invoice = new Invoice();
            $invoice->id = $i;
            $invoice->changeOwnerTo(\Auth::user());
            $invoice->invoice_number = rand(1000000, 3000000);

            $invoice->invoice_date = $faker->dateTimeBetween(Carbon::now()->subMonth(1), Carbon::now()->addMonth(2))->format('Y-m-d H:i:s');
            $invoice->due_date = Carbon::now()->addMonth(3);

            $invoice->invoice_status_id = rand(1, 5);

            $invoice->terms_and_cond = $faker->sentence();

            $invoice->notes = $faker->sentence();

            $invoice->from_company = $faker->company;
            $invoice->from_tax_number = rand(8000000, 10000000);
            $invoice->from_street = $faker->streetAddress;
            $invoice->from_city =  $faker->city;
            $invoice->from_state = 'New York';
            $invoice->from_country = $faker->country;
            $invoice->from_zip_code = $faker->postcode;


            $invoice->bill_to = $faker->company;
            $invoice->bill_tax_number = rand(8000000, 10000000);
            $invoice->bill_street = $faker->streetAddress;
            $invoice->bill_city =  $faker->city;
            $invoice->bill_state = 'New York';
            $invoice->bill_country = $faker->country;
            $invoice->bill_zip_code = $faker->postcode;

            $invoice->ship_to = $faker->company;
            $invoice->ship_tax_number = rand(8000000, 10000000);
            $invoice->ship_street = $faker->streetAddress;
            $invoice->ship_city =  $faker->city;
            $invoice->ship_state = 'New York';
            $invoice->ship_country = $faker->country;
            $invoice->ship_zip_code = $faker->postcode;

            $invoice->account_id = rand(21, 40);

            $invoice->discount = rand(100, 200);
            $invoice->currency_id = 96; //US
            $invoice->tax_id = rand(1, 3);
            $invoice->paid = rand(100, 300);
            $invoice->delivery_cost = rand(100, 300);
            $invoice->account_number = $faker->bankAccountNumber;

            $invoice->company_id = $this->secondCompany();

            $invoice->save();

            for ($j = 0; $j <= 3 ; $j++) {

                $product = Product::find(rand(21,40));

                $row = new InvoiceRow();
                $row->invoice_id = $invoice->id;
                $row->product_name = '#Product '.$product->name;
                $row->price = $product->price;
                $row->quantity = rand(1, 5);
                $row->company_id = $this->secondCompany();
                $row->product_id = $product->id;

                $row->save();
            }
        }
    }
}
