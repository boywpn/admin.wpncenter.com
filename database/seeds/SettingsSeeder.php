<?php

use Illuminate\Database\Seeder;

/**
 * Class SettingsSeeder
 */
class SettingsSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(\Modules\Platform\Settings\Database\Seeders\DateFormatSeeder::class);

        //TODO Refactor display seeder
        $this->call(\Modules\Platform\Settings\Database\Seeders\DisplaySeeder::class);

        $this->call(\Modules\Platform\Settings\Database\Seeders\LanguageSeeder::class);
        $this->call(\Modules\Platform\Settings\Database\Seeders\MenuManagerSeeder::class);
        $this->call(\Modules\Platform\Settings\Database\Seeders\TimeFormatSeeder::class);
        $this->call(\Modules\Platform\Settings\Database\Seeders\TimezoneSeeder::class);
        $this->call(\Modules\Platform\Settings\Database\Seeders\PermissionsSeeder::class);

        $this->call(\Modules\Platform\Settings\Database\Seeders\CompanySeeder::class);
        $this->call(\Modules\Platform\Settings\Database\Seeders\UserSeeder::class);

        $this->call(\Modules\Platform\Settings\Database\Seeders\CurrencySeeder::class);
        $this->call(\Modules\Platform\Settings\Database\Seeders\TaxSeeder::class);
    }
}
