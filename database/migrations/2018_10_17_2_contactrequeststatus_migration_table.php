<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ContactRequestStatusMigrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('contact_request_dict_contact_status', function (Blueprint $table) {

            $table->increments('id');


            $table->string('name')->nullable();


            $table->timestamps();
            $table->softDeletes();

        });

        $dictValues = [
            ['id' => 1, 'name' => 'New'],
            ['id' => 2, 'name' => 'In-progress'],
            ['id' => 3, 'name' => 'Contacted'],
            ['id' => 4, 'name' => 'Contact in future'],
            ['id' => 5, 'name' => 'Win'],
            ['id' => 6, 'name' => 'Lost'],
        ];

        DB::table('contact_request_dict_contact_status')->insert($dictValues);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists('contact_request_dict_contact_status');
    }
}
