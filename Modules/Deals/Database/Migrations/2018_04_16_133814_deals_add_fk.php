<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DealsAddFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deals', function (Blueprint $table) {

            $table->integer('deal_stage_id')->unsigned()->nullable()->change();
            $table->foreign('deal_stage_id')->references('id')->on('deals_dict_stage');

            $table->integer('deal_business_type_id')->unsigned()->nullable()->change();
            $table->foreign('deal_business_type_id')->references('id')->on('deals_dict_business_type');

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
