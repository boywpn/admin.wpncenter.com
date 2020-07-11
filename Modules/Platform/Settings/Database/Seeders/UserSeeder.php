<?php

namespace Modules\Platform\Settings\Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Platform\Core\Helper\CrudHelper;
use Modules\Platform\User\Entities\User;

/**
 * Class SettingsSeeder
 */
class UserSeeder extends Seeder
{

    private static $_GROUPS = [
        ['id' => 1, 'name' => 'Marketing Group', 'company_id' => 1],
        ['id' => 2, 'name' => 'Support Group', 'company_id' => 1],
        ['id' => 3, 'name' => 'Service Group', 'company_id' => 1],
        ['id' => 4, 'name' => 'Work Group', 'company_id' => 2],
        ['id' => 5, 'name' => 'Support Team', 'company_id' => 2],
        ['id' => 6, 'name' => 'Assault Team', 'company_id' => 2]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->clear();

        $this->addAdmin();
        $this->addCompanyUsers();
    }

    private function clear()
    {
        DB::table('model_has_roles')->truncate();

        DB::table('users')->truncate();

        DB::table('group_user')->truncate();
        DB::table('groups')->truncate();
    }

    private static $firstCompanyUsers = [
        [
            'id' => 2,
            'email' => 'norman@laravel-bap.com',
            'is_active' => 1,
            'first_name' => 'Norman',
            'last_name' => 'Osborn',
            'name' => 'Norman Osborn',
            'access_to_all_entity' => 1,
            'theme' => 'theme-indigo'
        ],
        [
            'id' => 3,
            'email' => 'harry@laravel-bap.com',
            'is_active' => 1,
            'first_name' => 'Harry',
            'last_name' => 'Osborn',
            'name' => 'Harry Osborn',
            'access_to_all_entity' => 1,
            'theme' => 'theme-green'
        ]
    ];

    private static $secondCompanyUsers = [
        [
            'id' => 4,
            'email' => 'wesker@laravel-bap.com',
            'is_active' => 1,
            'first_name' => 'Albert',
            'last_name' => 'Wesker',
            'name' => 'Albert Wesker',
            'access_to_all_entity' => 1,
            'theme' => 'theme-red'
        ],
        [
            'id' => 5,
            'email' => 'ada@laravel-bap.com',
            'is_active' => 1,
            'first_name' => 'Ada',
            'last_name' => 'Wong',
            'name' => 'Ada Wong',
            'access_to_all_entity' => 1,
            'theme' => 'theme-cyan'
        ]
    ];

    private function addAdmin()
    {
        $admin = [
            'id' => 1,
            'email' => 'admin@laravel-bap.com',
            'is_active' => 1,
            'first_name' => 'Jill',
            'last_name' => 'Valentine',
            'name' => 'Jill Valentine',
            'access_to_all_entity' => 1,
            'theme' => 'theme-indigo'
        ];

        $admin['password'] = \Illuminate\Support\Facades\Hash::make('admin');;
        $admin['created_at'] = Carbon::now();
        $admin['updated_at'] = Carbon::now();

        DB::table('users')->insert($admin);

        User::find(1)->syncRoles(1);
    }

    private function addCompanyUsers()
    {
        foreach (self::$firstCompanyUsers as $user) {
            $user['password'] = \Illuminate\Support\Facades\Hash::make('admin');
            $user['created_at'] = \Carbon\Carbon::now();
            $user['updated_at'] = \Carbon\Carbon::now();
            $user['company_id'] = 1;
            DB::table('users')->insert($user);
        }

        User::find(2)->syncRoles(2);
        User::find(3)->syncRoles(2);

        foreach (self::$secondCompanyUsers as $user) {
            $user['password'] = \Illuminate\Support\Facades\Hash::make('admin');
            $user['created_at'] = \Carbon\Carbon::now();
            $user['updated_at'] = \Carbon\Carbon::now();
            $user['company_id'] = 2;
            DB::table('users')->insert($user);
        }

        User::find(4)->syncRoles(2);
        User::find(5)->syncRoles(2);


        DB::table('groups')->insert(CrudHelper::setDatesInArray(self::$_GROUPS));
    }
}
