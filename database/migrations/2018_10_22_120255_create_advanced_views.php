<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvancedViews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bap_advanced_views', function (Blueprint $table) {

            $table->increments('id');

            $table->string('view_name')->nullable();
            $table->string('module_name')->nullable();
            $table->boolean('is_public')->nullable();
            $table->boolean('is_accepted')->nullable();
            $table->boolean('is_default')->nullable(); // only available for public views
            $table->integer('company_id');


            $table->text('defined_columns')->nullable();
            $table->text('filter_rules')->nullable();
            $table->integer('owner_id')->nullable();

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
        Schema::dropIfExists('bap_advanced_views');
    }
}
