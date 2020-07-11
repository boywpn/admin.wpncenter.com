<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserDefaultSelect extends Migration
{
    const TIME_ZONE_MIGRATION_DEFAULT = 'Europe/London';

    const DATE_FORMAT_MIGRATION_DEFAULT = 1; // YYYY-MM-DD

    const TIME_FORMAT_MIGRATION_DEFAULT = 1; // 24 hours

    const LANGUAGE_MIGRATION_DEFAULT = 1; // EN

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('time_format_id')->default(self::TIME_FORMAT_MIGRATION_DEFAULT)->change();
            $table->integer('date_format_id')->default(self::DATE_FORMAT_MIGRATION_DEFAULT)->change();
            $table->string('time_zone')->default(self::TIME_ZONE_MIGRATION_DEFAULT)->change();
            $table->integer('language_id')->default(self::LANGUAGE_MIGRATION_DEFAULT);
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
            $table->dropColumn('language_id');
        });
    }
}
