<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProductMigrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');


            $table->string('name')->nullable();


            $table->string('part_number')->nullable();


            $table->string('vendor_part_number')->nullable();


            $table->string('product_sheet')->nullable();


            $table->string('website')->nullable();


            $table->string('serial_no')->nullable();


            $table->decimal('price', 10, 2)->nullable();


            $table->nullableMorphs('owned_by');


            $table->integer('vendor_id')->nullable();


            $table->integer('product_type_id')->nullable();


            $table->integer('product_category_id')->nullable();


            $table->text('notes')->nullable();


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
        Schema::dropIfExists('products');
    }
}
