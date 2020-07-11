<?php

namespace Modules\Platform\Settings\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Platform\Core\Helper\SeederHelper;

/**
 * Class SettingsTimeFormatSeeder
 */
class TaxSeeder extends SeederHelper
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $taxData = [
            ['id' => 1, 'name' => '23%', 'tax_value' => 0.23],
            ['id' => 2, 'name' => '8%', 'tax_value' => 0.08],
            ['id' => 3, 'name' => '5%', 'tax_value' => 0.05],
        ];

        DB::table('bap_tax')->truncate();

        $this->saveOrUpdate('bap_tax', $taxData);
    }
}
