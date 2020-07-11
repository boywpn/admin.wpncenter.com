<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ServiceContractsAddFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('service_contracts', function (Blueprint $table) {

            $table->integer('service_contract_priority_id')->unsigned()->nullable()->change();
            $table->foreign('service_contract_priority_id')->references('id')->on('service_contracts_dict_priority');

            $table->integer('service_contract_status_id')->unsigned()->nullable()->change();
            $table->foreign('service_contract_status_id')->references('id')->on('service_contracts_dict_status');

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
