<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Status

        Schema::create('payments_dict_status', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });


        // Category

        Schema::create('payments_dict_category', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });




        //Payment method

        Schema::create('payments_dict_payment_method', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        // Payments table

        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->text('notes')->nullable();
            $table->date('payment_date');
            $table->float('amount');
            $table->boolean('income');


            $table->integer('payment_status_id')->nullable();
            $table->integer('payment_category_id')->nullable();
            $table->integer('payment_currency_id')->nullable();
            $table->integer('payment_payment_method_id')->nullable();

            $table->nullableMorphs('owned_by');

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
        Schema::dropIfExists('payments_dict_status');
        Schema::dropIfExists('payments_dict_category');
        Schema::dropIfExists('payments_dict_payment_method');
        Schema::dropIfExists('payments');
    }
}
