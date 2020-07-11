<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class QuotesAddFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotes', function (Blueprint $table) {

            $table->integer('quote_stage_id')->unsigned()->nullable()->change();
            $table->foreign('quote_stage_id')->references('id')->on('quotes_dict_stage');

            $table->integer('quote_carrier_id')->unsigned()->nullable()->change();
            $table->foreign('quote_carrier_id')->references('id')->on('quotes_dict_carrier');

            $table->integer('currency_id')->unsigned()->nullable()->change();
            $table->foreign('currency_id')->references('id')->on('bap_currency');

            $table->integer('tax_id')->unsigned()->nullable()->change();
            $table->foreign('tax_id')->references('id')->on('bap_tax');

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
