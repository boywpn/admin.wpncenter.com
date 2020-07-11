<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DealMigrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deals', function (Blueprint $table) {
            $table->increments('id');


            $table->string('name')->nullable();


            $table->nullableMorphs('owned_by');


            $table->string('amount')->nullable();


            $table->date('closing_date')->nullable();


            $table->string('probability')->nullable();


            $table->string('expected_revenue')->nullable();


            $table->string('next_step')->nullable();


            $table->integer('deal_stage_id')->nullable();


            $table->integer('business_type_id')->nullable();


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
        Schema::dropIfExists('deals');
    }
}
