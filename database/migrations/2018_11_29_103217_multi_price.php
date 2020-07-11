<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MultiPrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('price_list', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->nullableMorphs('owned_by');
            $table->integer('company_id')->nullable();

            $table->integer('product_id')->unsigned()->nullable();
            $table->foreign('product_id')->references('id')->on('products');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('invoices_rows', function (Blueprint $table) {
            $table->integer('price_list_id')->nullable();
        });



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('price_list');

        Schema::table('invoices_rows', function (Blueprint $table) {
            $table->dropColumn('price_list_id');
        });

    }
}
