<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ContactMigrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->increments('id');


            $table->nullableMorphs('owned_by');


            $table->string('first_name')->nullable();

            $table->string('last_name')->nullable();


            $table->string('full_name')->nullable();


            $table->string('job_title')->nullable();


            $table->string('department')->nullable();


            $table->integer('contact_status_id')->nullable();


            $table->integer('contact_source_id')->nullable();


            $table->string('phone')->nullable();


            $table->string('mobile')->nullable();


            $table->string('email')->nullable();


            $table->string('secondary_email')->nullable();


            $table->string('fax')->nullable();


            $table->string('assistant_name')->nullable();


            $table->string('assistant_phone')->nullable();


            $table->string('street')->nullable();


            $table->string('state')->nullable();


            $table->string('country')->nullable();


            $table->string('zip_code')->nullable();


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
        Schema::dropIfExists('contacts');
    }
}
