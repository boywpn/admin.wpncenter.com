<?php

namespace Modules\Documents\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Platform\Core\Helper\SeederHelper;

class DocumentsDatabaseSeeder extends SeederHelper
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $documents_dict_type = [
            ['id' => 1, 'name' => 'Internal'],
            ['id' => 2, 'name' => 'External'],
        ];

        DB::table('documents_dict_type')->truncate();

        $this->saveOrUpdate('documents_dict_type', $documents_dict_type);

        $documents_dict_category = [
            ['id' => 1, 'name' => 'Approval'],
            ['id' => 2, 'name' => 'Proposal'],
            ['id' => 3, 'name' => 'Quote'],
            ['id' => 4, 'name' => 'Contract'],
            ['id' => 5, 'name' => 'Invoice'],
            ['id' => 6, 'name' => 'Report'],
        ];

        DB::table('documents_dict_category')->truncate();

        $this->saveOrUpdate('documents_dict_category', $documents_dict_category);

        $documents_dict_status = [
            ['id' => 1, 'name' => 'New'],
            ['id' => 2, 'name' => 'In progress'],
            ['id' => 3, 'name' => 'Approved'],
            ['id' => 4, 'name' => 'Closed'],
        ];

        DB::table('documents_dict_status')->truncate();

        $this->saveOrUpdate('documents_dict_status', $documents_dict_status);
    }
}
