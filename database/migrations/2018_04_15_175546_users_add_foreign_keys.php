<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * User foreign keys
 *
 * Class UsersAddForeignKeys
 */
class UsersAddForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {

            $table->integer('time_format_id')->unsigned()->nullable()->change();
            $table->foreign('time_format_id')->references('id')->on('bap_time_format');

            $table->integer('date_format_id')->unsigned()->nullable()->change();
            $table->foreign('date_format_id')->references('id')->on('bap_date_format');

            $table->integer('language_id')->unsigned()->nullable()->change();
            $table->foreign('language_id')->references('id')->on('bap_language');
        });

        Schema::table('group_user', function (Blueprint $table) {

            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
