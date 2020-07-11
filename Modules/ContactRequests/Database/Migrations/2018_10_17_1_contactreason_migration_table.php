<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ContactReasonMigrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('contact_request_dict_contact_reason', function (Blueprint $table) {

            $table->increments('id');


            $table->string('name')->nullable();


            $table->timestamps();
            $table->softDeletes();

        });

        $dictValues = [
            ['id' => 1, 'name' => 'Want to know more about product'],
            ['id' => 2, 'name' => 'Interested in partnership'],
            ['id' => 3, 'name' => 'Need help or assistance'],
            ['id' => 4, 'name' => 'Have a complaint'],
            ['id' => 5, 'name' => 'Other'],
        ];

        DB::table('contact_request_dict_contact_reason')->insert($dictValues);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists('contact_request_dict_contact_reason');
    }
}
