<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class OrdersAddFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->integer('contact_id')->unsigned()->nullable()->change();
            $table->foreign('contact_id')->references('id')->on('contacts');

            $table->integer('account_id')->unsigned()->nullable()->change();
            $table->foreign('account_id')->references('id')->on('accounts');

            $table->integer('order_status_id')->unsigned()->nullable()->change();
            $table->foreign('order_status_id')->references('id')->on('orders_dict_status');

            $table->integer('order_carrier_id')->unsigned()->nullable()->change();
            $table->foreign('order_carrier_id')->references('id')->on('orders_dict_carrier');

            $table->integer('currency_id')->unsigned()->nullable()->change();
            $table->foreign('currency_id')->references('id')->on('bap_currency');

            $table->integer('tax_id')->unsigned()->nullable()->change();
            $table->foreign('tax_id')->references('id')->on('bap_tax');
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
