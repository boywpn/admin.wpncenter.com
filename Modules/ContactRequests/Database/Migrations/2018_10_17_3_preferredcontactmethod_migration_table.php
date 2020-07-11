<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PreferredContactMethodMigrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('contact_request_dict_contact_method', function (Blueprint $table) {

            $table->increments('id');


            $table->string('name')->nullable();


            $table->timestamps();
            $table->softDeletes();

        });

        $dictValues = [
            ['id' => 1, 'name' => 'Phone'],
            ['id' => 2, 'name' => 'E-mail'],
            ['id' => 3, 'name' => 'Other'],
        ];

        DB::table('contact_request_dict_contact_method')->insert($dictValues);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists('contact_request_dict_contact_method');
    }
}
