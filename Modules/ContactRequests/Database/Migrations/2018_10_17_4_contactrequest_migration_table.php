<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ContactRequestMigrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('contact_request', function (Blueprint $table) {

            $table->increments('id');

            $table->string('first_name')->nullable();

            $table->string('last_name')->nullable();

            $table->string('organization_name')->nullable();

            $table->string('phone_number')->nullable();

            $table->string('email')->nullable();

            $table->string('other_contact_method')->nullable();


            $table->string('custom_subject')->nullable();


            $table->dateTime('contact_date')->nullable();


            $table->dateTime('next_contact_date')->nullable();


            $table->nullableMorphs('owned_by');


            $table->integer('status_id')->unsigned()->nullable();
            $table->foreign('status_id')->references('id')->on('contact_request_dict_contact_status');


            $table->integer('preferred_id')->unsigned()->nullable();
            $table->foreign('preferred_id')->references('id')->on('contact_request_dict_contact_method');


            $table->integer('contact_reason_id')->unsigned()->nullable();
            $table->foreign('contact_reason_id')->references('id')->on('contact_request_dict_contact_reason');

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

        Schema::dropIfExists('contact_request');
    }
}
