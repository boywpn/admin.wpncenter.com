<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Artisan::call('module:publish');
        \Artisan::call('cache:clear');
        \Artisan::call('config:cache');
        $this->call(AllSeeder::class);
    }
}
