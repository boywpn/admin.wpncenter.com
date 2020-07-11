<?php

namespace Modules\Accounts\Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Accounts\Entities\Account;
use Modules\Platform\Core\Helper\SeederHelper;
use Modules\Platform\User\Entities\User;

class AccountDemoSeederTableSeeder extends SeederHelper

{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Account::truncate();

        \Auth::attempt(['email' => config('bap.demo_company_1'), 'password' => config('bap.demo_company_pass_1')]);

        for ($i = 1; $i <= 20; $i++) {
            $faker = Factory::create();

            $account = new Account();
            $account->id = $i;

            $account->name = $faker->company;
            $account->website = $faker->domainName;
            $account->annual_revenue = rand(100000, 2000000);
            $account->tax_number = rand(20000000, 90000000);
            $account->company_id = 1;

            $account->account_type_id = rand(1, 9);
            $account->account_industry_id = rand(1, 11);
            $account->account_rating_id = rand(1, 5);
            $account->phone = $faker->phoneNumber;
            $account->email = $faker->safeEmail;
            $account->street = $faker->streetAddress;
            $account->city = $faker->city;

            $account->country = $faker->country;
            $account->zip_code = $faker->postcode;
            $account->notes = $faker->sentence();

            $account->changeOwnerTo(\Auth::user());
            $account->company_id = $this->firstCompany();

            $account->save();
        }

        \Auth::attempt(['email' => config('bap.demo_company_2'), 'password' => config('bap.demo_company_pass_2')]);

        for ($i = 21; $i <= 40; $i++) {
            $faker = Factory::create();

            $account = new Account();
            $account->id = $i;

            $account->name = $faker->company;
            $account->website = $faker->domainName;
            $account->annual_revenue = rand(100000, 2000000);
            $account->tax_number = rand(20000000, 90000000);
            $account->company_id = 2;

            $account->account_type_id = rand(1, 9);
            $account->account_industry_id = rand(1, 11);
            $account->account_rating_id = rand(1, 5);
            $account->phone = $faker->phoneNumber;
            $account->email = $faker->safeEmail;
            $account->street = $faker->streetAddress;
            $account->city = $faker->city;

            $account->country = $faker->country;
            $account->zip_code = $faker->postcode;
            $account->notes = $faker->sentence();

            $account->changeOwnerTo(\Auth::user());
            $account->company_id = $this->secondCompany();

            $account->save();
        }
    }
}
