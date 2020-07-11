<?php

namespace Modules\Products\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Platform\Core\Helper\SeederHelper;

class ProductsDatabaseSeeder extends SeederHelper
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
            ['id' => 1, 'name' => 'Hardware'],
            ['id' => 2, 'name' => 'Software'],
            ['id' => 3, 'name' => 'Other'],
        ];

        DB::table('products_dict_category')->truncate();

        $this->saveOrUpdate('products_dict_category', $dictValues);


        $dictValues = [
            ['id' => 1, 'name' => 'Product'],
            ['id' => 2, 'name' => 'Service'],
        ];

        DB::table('products_dict_type')->truncate();

        $this->saveOrUpdate('products_dict_type', $dictValues);
    }
}
