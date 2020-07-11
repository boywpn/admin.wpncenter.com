<?php

namespace Modules\Contacts\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Modules\Platform\Core\Helper\SeederHelper;

class ContactsDatabaseSeeder extends SeederHelper
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $contacts_dict_source = [
            ['id' => 1, 'name' => 'Cold call','icon' => 'fa fa-phone', 'color' => 'col-grey'],
            ['id' => 2, 'name' => 'Existing customer','icon' => 'fa fa-user-o', 'color' =>  'col-grey'],
            ['id' => 3, 'name' => 'Self generated','icon' => 'fa fa-building-o', 'color' =>  'col-grey'],
            ['id' => 4, 'name' => 'Employee/Partner','icon' => 'fa fa-users', 'color' =>  'col-grey'],
            ['id' => 5, 'name' => 'Public relations','icon' => 'fa fa-bullhorn', 'color' =>  'col-grey'],
            ['id' => 6, 'name' => 'Direct mail','icon' => 'fa fa-mail', 'color' =>  'col-grey'],
            ['id' => 7, 'name' => 'Conference','icon' => 'fa calendar-o', 'color' =>  'col-grey'],
            ['id' => 8, 'name' => 'Trade show','icon' => 'fa fa-vcard-o', 'color' =>  'col-grey'],
            ['id' => 9, 'name' => 'Web site','icon' => 'fa fa-globe', 'color' =>  'col-grey'],
            ['id' => 10, 'name' => 'Word of mouth','icon' => 'fa fa-smile-o', 'color' =>  'col-grey'],
            ['id' => 11, 'name' => 'Other','icon' => 'fa fa-circle', 'color' =>  'col-grey'],
        ];

        DB::table('contacts_dict_source')->truncate();

        $this->saveOrUpdate('contacts_dict_source', $contacts_dict_source);

        $contacts_dict_status = [
            ['id' => 1, 'name' => 'New','icon' => 'fa fa-user-o', 'color' => 'col-orange'],
            ['id' => 2, 'name' => 'Approved','icon' => 'fa fa-check', 'color' => 'col-green'],
            ['id' => 3, 'name' => 'In progress','icon' => 'fa fa-briefcase', 'color' => 'col-blue'],
            ['id' => 4, 'name' => 'Test','icon' => 'fa fa-adjust', 'color' => 'col-pink'],
            ['id' => 5, 'name' => 'Trash','icon' => 'fa fa-trash-o', 'color' => 'col-grey'],
        ];

        DB::table('contacts_dict_status')->truncate();

        $this->saveOrUpdate('contacts_dict_status', $contacts_dict_status);
    }
}
