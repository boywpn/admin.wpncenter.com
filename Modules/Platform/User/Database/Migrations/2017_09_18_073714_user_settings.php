<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * User timezone
 * User datetime
 * User time settings
 *
 * Class UserLocation
 */
class UserSettings extends Migration
{


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bap_date_format', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('details')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('bap_time_format', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('details')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('bap_time_zone', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('php_timezone');
            $table->boolean('is_active');
            $table->timestamps();
            $table->softDeletes();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('bap_time_format');
        Schema::drop('bap_date_format');
        Schema::drop('bap_time_zone');
    }
}
