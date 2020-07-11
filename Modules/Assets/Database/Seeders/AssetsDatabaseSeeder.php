<?php

namespace Modules\Assets\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Platform\Core\Helper\SeederHelper;

class AssetsDatabaseSeeder extends SeederHelper
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $assets_dict_category = [
            ['id' => 1, 'name' => 'Phone'],
            ['id' => 2, 'name' => 'Computer'],
            ['id' => 3, 'name' => 'License'],
            ['id' => 4, 'name' => 'Car'],
        ];

        DB::table('assets_dict_category')->truncate();

        $this->saveOrUpdate('assets_dict_category', $assets_dict_category);

        $assets_dict_manufacturer = [
            ['id' => 1, 'name' => 'Apple'],
            ['id' => 2, 'name' => 'Samsung'],
            ['id' => 3, 'name' => 'Toyota'],
            ['id' => 4, 'name' => 'Skoda'],
            ['id' => 5, 'name' => 'Nokia'],
        ];

        DB::table('assets_dict_manufacturer')->truncate();

        $this->saveOrUpdate('assets_dict_manufacturer', $assets_dict_manufacturer);

        $assets_dict_status = [
            ['id' => 1, 'name' => 'Ready to deploy'],
            ['id' => 2, 'name' => 'Deployed'],
            ['id' => 3, 'name' => 'Pending'],
            ['id' => 4, 'name' => 'Out for repair'],
            ['id' => 5, 'name' => 'Out for diagnostics'],
            ['id' => 6, 'name' => 'Broken not fixable'],
            ['id' => 7, 'name' => 'Lost/stolen'],
            ['id' => 8, 'name' => 'On order'],
            ['id' => 9, 'name' => 'In stock'],
        ];

        DB::table('assets_dict_status')->truncate();

        $this->saveOrUpdate('assets_dict_status', $assets_dict_status);
    }
}
