<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InvoiceMigrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');


            $table->string('invoice_number')->nullable();


            $table->integer('order_id')->nullable();


            $table->string('customer_no')->nullable();


            $table->integer('contact_id')->nullable();


            $table->integer('account_id')->nullable();


            $table->date('invoice_date')->nullable();


            $table->date('due_date')->nullable();


            $table->nullableMorphs('owned_by');


            $table->integer('invoice_status_id')->nullable();

            $table->string('from_company')->nullable();
            $table->string('from_tax_number')->nullable();

            $table->string('from_street')->nullable();

            $table->string('from_city')->nullable();

            $table->string('from_state')->nullable();

            $table->string('from_country')->nullable();

            $table->string('from_zip_code')->nullable();


            $table->string('bill_to')->nullable();
            $table->string('bill_tax_number')->nullable();

            $table->string('bill_street')->nullable();


            $table->string('bill_state')->nullable();


            $table->string('bill_country')->nullable();


            $table->string('bill_zip_code')->nullable();

            $table->string('ship_to')->nullable();

            $table->string('ship_tax_number')->nullable();

            $table->string('ship_street')->nullable();


            $table->string('ship_state')->nullable();


            $table->string('ship_country')->nullable();


            $table->string('ship_zip_code')->nullable();


            $table->text('terms_and_cond')->nullable();


            $table->text('notes')->nullable();

            $table->float('discount')->nullable();

            $table->integer('currency_id')->nullable();
            $table->integer('tax_id')->nullable();

            $table->float('paid')->nullable();

            $table->string('account_number')->nullable();
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
        Schema::dropIfExists('invoices');
    }
}
