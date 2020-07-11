<?php

namespace Modules\Quotes\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Platform\Core\Helper\SeederHelper;

class QuotesDatabaseSeeder extends SeederHelper
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $dictValues = [
            ['id' => 1, 'name' => 'Fedex'],
            ['id' => 2, 'name' => 'UPS'],
            ['id' => 3, 'name' => 'Upss'],
            ['id' => 4, 'name' => 'DHL'],
            ['id' => 5, 'name' => 'Bluedart'],
            ['id' => 6, 'name' => 'Other'],
        ];

        DB::table('quotes_dict_carrier')->truncate();

        $this->saveOrUpdate('quotes_dict_carrier', $dictValues);


        $dictValues = [
            ['id' => 1, 'name' => 'Created'],
            ['id' => 2, 'name' => 'Delivered'],
            ['id' => 3, 'name' => 'Reviewed'],
            ['id' => 4, 'name' => 'Accepted'],
            ['id' => 5, 'name' => 'Rejected'],
        ];

        DB::table('quotes_dict_stage')->truncate();

        $this->saveOrUpdate('quotes_dict_stage', $dictValues);
    }
}
