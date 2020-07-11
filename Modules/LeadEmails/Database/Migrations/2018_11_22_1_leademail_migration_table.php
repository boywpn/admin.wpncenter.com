<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LeadEmailMigrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('lead_email', function (Blueprint $table) {

            $table->increments('id');

            $table->string('email')->nullable();

            $table->boolean('is_default')->default(0);

            $table->boolean('is_active')->default(0);

            $table->boolean('is_marketing')->default(0);

            $table->integer('lead_id')->unsigned()->nullable();
            $table->foreign('lead_id')->references('id')->on('leads')->onDelete('cascade');


            $table->integer('company_id')->nullable();

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

        Schema::dropIfExists('lead_email');
    }
}
