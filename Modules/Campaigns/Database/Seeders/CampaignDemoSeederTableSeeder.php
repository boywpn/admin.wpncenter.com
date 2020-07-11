<?php

namespace Modules\Campaigns\Database\Seeders;

use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Campaigns\Entities\Campaign;
use Modules\Platform\Core\Helper\SeederHelper;

class CampaignDemoSeederTableSeeder extends SeederHelper
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

        Campaign::truncate();

        \Auth::attempt(['email' => config('bap.demo_company_1'), 'password' => config('bap.demo_company_pass_1')]);

        for ($i = 1; $i <= 20; $i++) {
            $faker = Factory::create();

            $campaign = new Campaign();
            $campaign->id = $i;

            $campaign->name = 'Lead campaign for '.$faker->company;
            $campaign->product = $faker->sentence(2);
            $campaign->target_audience = rand(100, 2000);
            $campaign->expected_close_date = Carbon::now()->format('Y-m-d h:i:s');
            $campaign->sponsor = $faker->company;
            $campaign->target_size = rand(100, 2000);

            $campaign->campaign_status_id = rand(1, 5);
            $campaign->campaign_type_id = rand(1, 11);
            $campaign->budget_cost = rand(5000, 65400);
            $campaign->actual_budget = rand(5000, 50000);
            $campaign->expected_response = rand(4000, 35959);
            $campaign->expected_revenue = rand(344, 4000);
            $campaign->expected_sales_count = rand(3453, 39593);
            $campaign->actual_sales_count = rand(3456, 5884);
            $campaign->expected_response_count = rand(100, 3949);
            $campaign->actual_response_count = rand(23, 4949);
            $campaign->expected_roi = rand(344, 5969);
            $campaign->actual_roi = rand(2344, 595959);
            $campaign->notes = $faker->sentence();

            $campaign->changeOwnerTo(\Auth::user());
            $campaign->company_id = $this->firstCompany();

            $campaign->save();
        }

        \Auth::attempt(['email' => config('bap.demo_company_2'), 'password' => config('bap.demo_company_pass_2')]);

        for ($i = 21; $i <= 40; $i++) {
            $faker = Factory::create();

            $campaign = new Campaign();
            $campaign->id = $i;

            $campaign->name = 'Lead campaign for '.$faker->company;
            $campaign->product = $faker->sentence(2);
            $campaign->target_audience = rand(100, 2000);
            $campaign->expected_close_date = Carbon::now()->format('Y-m-d h:i:s');
            $campaign->sponsor = $faker->company;
            $campaign->target_size = rand(100, 2000);

            $campaign->campaign_status_id = rand(1, 5);
            $campaign->campaign_type_id = rand(1, 11);
            $campaign->budget_cost = rand(5000, 65400);
            $campaign->actual_budget = rand(5000, 50000);
            $campaign->expected_response = rand(4000, 35959);
            $campaign->expected_revenue = rand(344, 4000);
            $campaign->expected_sales_count = rand(3453, 39593);
            $campaign->actual_sales_count = rand(3456, 5884);
            $campaign->expected_response_count = rand(100, 3949);
            $campaign->actual_response_count = rand(23, 4949);
            $campaign->expected_roi = rand(344, 5969);
            $campaign->actual_roi = rand(2344, 595959);
            $campaign->notes = $faker->sentence();

            $campaign->changeOwnerTo(\Auth::user());
            $campaign->company_id = $this->secondCompany();

            $campaign->save();
        }
    }
}
