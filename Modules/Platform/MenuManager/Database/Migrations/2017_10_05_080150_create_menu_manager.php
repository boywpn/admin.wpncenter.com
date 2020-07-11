<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuManager extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bap_menu', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('order_by')->default(1);
            $table->string('url');
            $table->string('label');
            $table->string('icon');
            $table->string('permission');
            $table->integer('parent_id')->nullable();
            $table->integer('section')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bap_menu');
    }
}
