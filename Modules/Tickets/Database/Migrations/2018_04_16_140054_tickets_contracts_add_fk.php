<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TicketsContractsAddFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {

            $table->integer('ticket_priority_id')->unsigned()->nullable()->change();
            $table->foreign('ticket_priority_id')->references('id')->on('tickets_dict_priority');

            $table->integer('ticket_status_id')->unsigned()->nullable()->change();
            $table->foreign('ticket_status_id')->references('id')->on('tickets_dict_status');

            $table->integer('ticket_severity_id')->unsigned()->nullable()->change();
            $table->foreign('ticket_severity_id')->references('id')->on('tickets_dict_severity');

            $table->integer('ticket_category_id')->unsigned()->nullable()->change();
            $table->foreign('ticket_category_id')->references('id')->on('tickets_dict_category');

            $table->integer('contact_id')->unsigned()->nullable()->change();
            $table->foreign('contact_id')->references('id')->on('contacts');

            $table->integer('account_id')->unsigned()->nullable()->change();
            $table->foreign('account_id')->references('id')->on('accounts');
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
