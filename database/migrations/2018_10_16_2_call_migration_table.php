<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CallMigrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('calls', function (Blueprint $table) {

            $table->increments('id');

            $table->string('subject')->nullable();

            $table->string('phone_number')->nullable();

            $table->string('duration')->nullable();

            $table->nullableMorphs('owned_by');

            $table->dateTime('call_date')->nullable();

            $table->integer('account_id')->unsigned()->nullable();
            $table->foreign('account_id')->references('id')->on('accounts');

            $table->integer('contact_id')->unsigned()->nullable();
            $table->foreign('contact_id')->references('id')->on('contacts');

            $table->integer('lead_id')->unsigned()->nullable();
            $table->foreign('lead_id')->references('id')->on('leads');

            $table->integer('company_id')->nullable();

            $table->integer('direction_id')->unsigned()->nullable();
            $table->foreign('direction_id')->references('id')->on('calls_dict_direction');

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

        Schema::dropIfExists('calls');
    }
}
