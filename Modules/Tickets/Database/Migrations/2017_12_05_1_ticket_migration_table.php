<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TicketMigrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->increments('id');


            $table->string('name')->nullable();


            $table->date('due_date')->nullable();


            $table->nullableMorphs('owned_by');


            $table->integer('ticket_priority_id')->nullable();


            $table->integer('ticket_status_id')->nullable();


            $table->integer('ticket_severity_id')->nullable();


            $table->integer('ticket_category_id')->nullable();


            $table->text('description')->nullable();


            $table->text('resolution')->nullable();


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
        Schema::dropIfExists('tickets');
    }
}
