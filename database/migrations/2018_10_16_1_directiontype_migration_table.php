<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DirectionTypeMigrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('calls_dict_direction', function (Blueprint $table) {

            $table->increments('id');

            $table->string('name')->nullable();

            $table->timestamps();
            $table->softDeletes();

        });

        $dictValues = [
            ['id' => 1, 'name' => 'Incoming'],
            ['id' => 2, 'name' => 'Outgoing'],
        ];

        DB::table('calls_dict_direction')->insert($dictValues);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists('calls_dict_direction');
    }
}
