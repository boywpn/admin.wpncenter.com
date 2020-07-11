<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserPreferences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_active')->default(1);

            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('full_name')->nullable();

            $table->string('title')->nullable();
            $table->string('department')->nullable();
            $table->string('office_phone')->nullable();
            $table->string('mobile_phone')->nullable();
            $table->string('home_phone')->nullable();
            $table->string('signature')->nullable();
            $table->string('fax')->nullable();
            $table->string('secondary_email')->nullable();

            $table->boolean('left_panel_hide')->default(0);
            $table->string('theme')->default('theme-red')->nullable();

            $table->string('address_country')->nullable();
            $table->string('address_state')->nullable();
            $table->string('address_city')->nullable();
            $table->string('address_postal_code')->nullable();
            $table->string('address_street')->nullable();

            $table->integer('time_format_id')->nullable();
            $table->integer('date_format_id')->nullable();
            $table->string('time_zone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_active');

            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('full_name');

            $table->dropColumn('title');
            $table->dropColumn('department');
            $table->dropColumn('office_phone');
            $table->dropColumn('mobile_phone');
            $table->dropColumn('home_phone');
            $table->dropColumn('signature');
            $table->dropColumn('fax');
            $table->dropColumn('secondary_email');

            $table->dropColumn('left_panel_hide');
            $table->dropColumn('theme');

            $table->dropColumn('address_country');
            $table->dropColumn('address_state');
            $table->dropColumn('address_city');
            $table->dropColumn('address_postal_code');
            $table->dropColumn('address_street');

            $table->dropColumn('time_format_id');
            $table->dropColumn('date_format_id');
            $table->dropColumn('time_zone');
        });

        DB::unprepared('DROP TRIGGER `user_full_name_insert`');
        DB::unprepared('DROP TRIGGER `user_full_name_update`');
    }
}
