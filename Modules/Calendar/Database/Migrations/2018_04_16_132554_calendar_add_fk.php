<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CalendarAddFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bap_calendar_event', function (Blueprint $table) {

            $table->integer('calendar_id')->unsigned()->nullable()->change();
            $table->foreign('calendar_id')->references('id')->on('bap_calendar');

            $table->integer('event_priority_id')->unsigned()->nullable()->change();
            $table->foreign('event_priority_id')->references('id')->on('bap_calendar_dict_event_priority');

            $table->integer('event_status_id')->unsigned()->nullable()->change();
            $table->foreign('event_status_id')->references('id')->on('bap_calendar_dict_event_status');

            $table->integer('created_by')->unsigned()->nullable()->change();
            $table->foreign('created_by')->references('id')->on('users');

        });

        Schema::table('event_user', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->integer('event_id')->unsigned()->change();
            $table->foreign('event_id')->references('id')->on('bap_calendar_event')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
