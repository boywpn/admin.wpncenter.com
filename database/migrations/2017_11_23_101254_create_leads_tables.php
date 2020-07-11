<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Lead status

        Schema::create('leads_dict_status', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });



        //Lead source

        Schema::create('leads_dict_source', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });



        //Lead industry

        Schema::create('leads_dict_industry', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });



        //Lead rating

        Schema::create('leads_dict_rating', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });



        Schema::create('leads', function (Blueprint $table) {
            $table->increments('id');

            $table->string('first_name');
            $table->string('last_name');
            $table->string('full_name');
            $table->string('email')->nullable();
            $table->string('fax')->nullable();
            $table->string('annual_revenue')->nullable();
            $table->string('website')->nullable();
            $table->string('no_of_employees')->nullable();
            $table->string('skype')->nullable();
            $table->string('company')->nullable();
            $table->string('job_title')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('secondary_email')->nullable();
            $table->string('twitter')->nullable();
            $table->string('facebook')->nullable();

            $table->text('description')->nullable();

            $table->string('addr_street')->nullable();
            $table->string('addr_state')->nullable();
            $table->string('addr_country')->nullable();
            $table->string('addr_city')->nullable();
            $table->string('addr_zip')->nullable();


            $table->integer('lead_status_id')->nullable();
            $table->integer('lead_source_id')->nullable();
            $table->integer('lead_industry_id')->nullable();
            $table->integer('lead_rating_id')->nullable();

            $table->nullableMorphs('owned_by');

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
        Schema::dropIfExists('leads_dict_status');
        Schema::dropIfExists('leads_dict_source');
        Schema::dropIfExists('leads_dict_industry');
        Schema::dropIfExists('leads_dict_rating');

        Schema::dropIfExists('leads');
    }
}
