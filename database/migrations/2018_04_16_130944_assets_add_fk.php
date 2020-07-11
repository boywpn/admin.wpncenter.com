<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AssetsAddFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assets', function (Blueprint $table) {

            $table->integer('asset_status_id')->unsigned()->nullable()->change();
            $table->foreign('asset_status_id')->references('id')->on('assets_dict_status');

            $table->integer('asset_category_id')->unsigned()->nullable()->change();
            $table->foreign('asset_category_id')->references('id')->on('assets_dict_category');

            $table->integer('asset_manufacturer_id')->unsigned()->nullable()->change();
            $table->foreign('asset_manufacturer_id')->references('id')->on('assets_dict_manufacturer');

            $table->integer('contact_id')->unsigned()->nullable()->change();
            $table->foreign('contact_id')->references('id')->on('contacts');

            $table->integer('account_id')->unsigned()->nullable()->change();
            $table->foreign('account_id')->references('id')->on('accounts');
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
