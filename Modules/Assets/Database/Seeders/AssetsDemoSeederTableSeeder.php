<?php

namespace Modules\Assets\Database\Seeders;

use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Assets\Entities\Asset;
use Modules\Platform\Core\Helper\SeederHelper;
use Modules\Platform\User\Entities\User;

class AssetsDemoSeederTableSeeder extends SeederHelper
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Asset::truncate();

        \Auth::attempt(['email' => config('bap.demo_company_1'), 'password' => config('bap.demo_company_pass_1')]);

        for ($i = 1; $i <= 50; $i++) {
            $faker = Factory::create();

            $asset = new Asset();
            $asset->id = $i;

            $asset->name = $faker->randomElement(array('Xiaomi Redmi 5A', 'Apple iPhone 8', 'OnePlus 6'));
            $asset->model_no = rand(100, 349955);
            $asset->tag_number = 'TG' . rand(39494, 49598585);
            $asset->order_number = rand(100, 4995);

            $asset->purchase_date = $faker->dateTimeBetween(Carbon::now()->subMonth(1), Carbon::now()->addMonth(2))->format('Y-m-d H:i:s');
            $asset->purchase_cost = rand(100, 4593);

            $asset->asset_status_id = rand(1, 9);
            $asset->asset_category_id = rand(1, 4);
            $asset->asset_manufacturer_id = rand(1, 5);

            $asset->notes = $faker->sentence();

            $asset->changeOwnerTo(\Auth::user());
            $asset->account_id = rand(1,20);
            $asset->company_id = $this->firstCompany();

            $asset->save();
        }

        \Auth::attempt(['email' => config('bap.demo_company_2'), 'password' => config('bap.demo_company_pass_2')]);

        for ($i = 51; $i <= 100; $i++) {
            $faker = Factory::create();

            $asset = new Asset();
            $asset->id = $i;

            $asset->name = $faker->randomElement(array('Xiaomi Redmi 6A', 'Apple iPhone X', 'OnePlus 7'));
            $asset->model_no = rand(100, 349955);
            $asset->tag_number = 'TGNUM' . rand(39494, 49598585);
            $asset->order_number = rand(100, 4995);

            $asset->purchase_date = $faker->dateTimeBetween(Carbon::now()->subMonth(1), Carbon::now()->addMonth(2))->format('Y-m-d H:i:s');
            $asset->purchase_cost = rand(100, 4593);

            $asset->asset_status_id = rand(1, 9);
            $asset->asset_category_id = rand(1, 4);
            $asset->asset_manufacturer_id = rand(1, 5);

            $asset->notes = $faker->sentence();

            $asset->changeOwnerTo(\Auth::user());
            $asset->account_id = rand(21,40);
            $asset->company_id = $this->secondCompany();

            $asset->save();
        }


    }
}
