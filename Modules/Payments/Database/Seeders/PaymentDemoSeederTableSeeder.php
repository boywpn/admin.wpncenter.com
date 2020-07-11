<?php

namespace Modules\Payments\Database\Seeders;

use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Payments\Entities\Payment;
use Modules\Platform\Core\Helper\SeederHelper;
use Modules\Platform\User\Entities\User;
use Spatie\Activitylog\Models\Activity;

class PaymentDemoSeederTableSeeder extends SeederHelper
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Payment::truncate();

        \Auth::attempt(['email' => config('bap.demo_company_1'), 'password' => config('bap.demo_company_pass_1')]);


        for ($i = 1; $i <= 50; $i++) {

            $faker = Factory::create();

            $payment = new Payment();
            $payment->id = $i;

            $payment->name = $faker->company;
            $payment->notes = $faker->sentence();

            $payment->payment_date = $faker->dateTimeBetween(Carbon::now()->subMonth(3))->format('Y-m-d H:i:s');
            $payment->amount = rand(100, 4000);
            $payment->income = rand(0, 1);
            $payment->payment_status_id = rand(1, 3);
            $payment->payment_category_id = rand(1, 7);
            $payment->payment_currency_id = 96; //USD
            $payment->payment_payment_method_id = rand(1, 4);
            $payment->changeOwnerTo(\Auth::user());
            $payment->company_id = $this->firstCompany();

            $payment->save();

        }

        \Auth::attempt(['email' => config('bap.demo_company_2'), 'password' => config('bap.demo_company_pass_2')]);


        for ($i = 51; $i <= 100; $i++) {

            $faker = Factory::create();

            $payment = new Payment();
            $payment->id = $i;

            $payment->name = $faker->company;
            $payment->notes = $faker->sentence();

            $payment->payment_date = $faker->dateTimeBetween(Carbon::now()->subMonth(3))->format('Y-m-d H:i:s');
            $payment->amount = rand(100, 4000);
            $payment->income = rand(0, 1);
            $payment->payment_status_id = rand(1, 3);
            $payment->payment_category_id = rand(1, 7);
            $payment->payment_currency_id = 96; //USD
            $payment->payment_payment_method_id = rand(1, 4);
            $payment->changeOwnerTo(\Auth::user());
            $payment->company_id = $this->secondCompany();

            $payment->save();

        }
    }
}
