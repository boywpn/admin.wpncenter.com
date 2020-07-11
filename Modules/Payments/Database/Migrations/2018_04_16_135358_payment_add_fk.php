<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PaymentAddFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {

            $table->integer('payment_status_id')->unsigned()->nullable()->change();
            $table->foreign('payment_status_id')->references('id')->on('payments_dict_status');

            $table->integer('payment_category_id')->unsigned()->nullable()->change();
            $table->foreign('payment_category_id')->references('id')->on('payments_dict_category');

            $table->integer('payment_currency_id')->unsigned()->nullable()->change();
            $table->foreign('payment_currency_id')->references('id')->on('bap_currency');

            $table->integer('payment_payment_method_id')->unsigned()->nullable()->change();
            $table->foreign('payment_payment_method_id')->references('id')->on('payments_dict_payment_method');

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
