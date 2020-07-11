<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InvoicesAddFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {

            $table->integer('order_id')->unsigned()->nullable()->change();
            $table->foreign('order_id')->references('id')->on('orders');

            $table->integer('contact_id')->unsigned()->nullable()->change();
            $table->foreign('contact_id')->references('id')->on('contacts');

            $table->integer('account_id')->unsigned()->nullable()->change();
            $table->foreign('account_id')->references('id')->on('accounts');

            $table->integer('invoice_status_id')->unsigned()->nullable()->change();
            $table->foreign('invoice_status_id')->references('id')->on('invoices_dict_status');

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
