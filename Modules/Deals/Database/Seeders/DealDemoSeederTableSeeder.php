<?php

namespace Modules\Deals\Database\Seeders;

use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Deals\Entities\Deal;
use Modules\Platform\Core\Helper\SeederHelper;

class DealDemoSeederTableSeeder extends SeederHelper
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Model::unguard();

        Deal::truncate();

        \Auth::attempt(['email' => config('bap.demo_company_1'), 'password' => config('bap.demo_company_pass_1')]);


        for ($i = 1; $i <= 50; $i++) {
            $faker = Factory::create();

            $deal = new Deal();
            $deal->id = $i;
            $deal->changeOwnerTo(\Auth::user());

            $deal->name = 'Deal for '.$faker->company;
            $deal->amount = rand(1000, 67889);
            $deal->closing_date = $faker->dateTimeBetween(Carbon::now()->subMonth(1), Carbon::now()->addMonth(2))->format('Y-m-d H:i:s');
            $deal->probability = rand(1, 100);
            $deal->expected_revenue = rand(2000, 67852);
            $deal->next_step = $faker->sentence();
            $deal->deal_stage_id = rand(1, 9);
            $deal->deal_business_type_id = rand(1, 2);
            $deal->notes = $faker->sentence();

            $deal->account_id = rand(1, 20);

            $deal->company_id = $this->firstCompany();

            $deal->save();
        }

        \Auth::attempt(['email' => config('bap.demo_company_2'), 'password' => config('bap.demo_company_pass_2')]);

        for ($i = 51; $i <= 100; $i++) {
            $faker = Factory::create();

            $deal = new Deal();
            $deal->id = $i;
            $deal->changeOwnerTo(\Auth::user());

            $deal->name = 'Deal for '.$faker->company;
            $deal->amount = rand(1000, 67889);
            $deal->closing_date = $faker->dateTimeBetween(Carbon::now()->subMonth(1), Carbon::now()->addMonth(2))->format('Y-m-d H:i:s');
            $deal->probability = rand(1, 100);
            $deal->expected_revenue = rand(2000, 67852);
            $deal->next_step = $faker->sentence();
            $deal->deal_stage_id = rand(1, 9);
            $deal->deal_business_type_id = rand(1, 2);
            $deal->notes = $faker->sentence();

            $deal->account_id = rand(21, 40);

            $deal->company_id = $this->secondCompany();

            $deal->save();
        }
    }
}
