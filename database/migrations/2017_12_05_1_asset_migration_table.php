<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AssetMigrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->increments('id');


            $table->string('name')->nullable();


            $table->string('model_no')->nullable();


            $table->string('tag_number')->nullable();


            $table->string('order_number')->nullable();


            $table->date('purchase_date')->nullable();


            $table->decimal('purchase_cost', 10, 2)->nullable();


            $table->nullableMorphs('owned_by');


            $table->integer('asset_status_id')->nullable();


            $table->integer('asset_category_id')->nullable();


            $table->integer('asset_manufacturer_id')->nullable();


            $table->integer('supplier_id')->nullable();


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
        Schema::dropIfExists('assets');
    }
}
