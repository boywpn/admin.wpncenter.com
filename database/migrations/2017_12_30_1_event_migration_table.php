<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EventMigrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bap_calendar_event', function (Blueprint $table) {
            $table->increments('id');


            $table->string('name')->nullable();


            $table->boolean('is_public')->nullable();


            $table->nullableMorphs('owned_by');


            $table->date('start_date')->nullable();


            $table->date('end_date')->nullable();


            $table->boolean('full_day')->nullable();


            $table->string('event_color')->nullable();


            $table->integer('calendar_id')->nullable();


            $table->integer('event_priority_id')->nullable();


            $table->integer('event_status_id')->nullable();


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
        Schema::dropIfExists('bap_calendar_event');
    }
}
