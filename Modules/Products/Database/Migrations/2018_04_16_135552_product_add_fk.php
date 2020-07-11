<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductAddFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {

            $table->integer('vendor_id')->unsigned()->nullable()->change();
            $table->foreign('vendor_id')->references('id')->on('vendors');

            $table->integer('product_type_id')->unsigned()->nullable()->change();
            $table->foreign('product_type_id')->references('id')->on('products_dict_type');

            $table->integer('product_category_id')->unsigned()->nullable()->change();
            $table->foreign('product_category_id')->references('id')->on('products_dict_category');

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
