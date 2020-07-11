<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class QuoteMigrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->increments('id');


            $table->string('name')->nullable();


            $table->nullableMorphs('owned_by');


            $table->date('valid_unitl')->nullable();


            $table->string('shipping')->nullable();


            $table->integer('quote_stage_id')->nullable();


            $table->integer('quote_carrier_id')->nullable();


            $table->string('street')->nullable();


            $table->string('state')->nullable();


            $table->string('country')->nullable();


            $table->string('zip_code')->nullable();


            $table->text('notes')->nullable();

            $table->float('discount')->nullable();

            $table->integer('currency_id')->nullable();
            $table->integer('tax_id')->nullable();

            $table->float('delivery_cost')->nullable();


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
        Schema::dropIfExists('quotes');
    }
}
